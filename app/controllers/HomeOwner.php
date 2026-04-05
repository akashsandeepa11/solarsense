<?php
class HomeOwner extends Controller
{
    private $serviceModel;
    private $smsModel;
    private $solarSystemModel;
    private $dashboardModel;
    private $inventoryModel;
    private $profileModel;

    private $user = [
        'role' => ROLE_HOMEOWNER,
    ];

    public function __construct()
    {
        $this->serviceModel      = $this->model('M_Service');
        $this->smsModel          = $this->model('M_SMS');
        $this->solarSystemModel  = $this->model('M_SolarSystem');
        $this->dashboardModel    = $this->model('M_Homeowner_Dashboard');
        $this->inventoryModel    = $this->model('M_Inventory');
        $this->profileModel      = $this->model('M_Profile');
    }


    public function dashboard($page = 'index')
    {
        if ($page == 'index') {
        
            $userId     = (int) $_SESSION['user_id'];
            $availYears = $this->smsModel->get_available_years($userId);
            $stats = $this->dashboardModel->getStats($_SESSION['user_id']);
            $system = $this->solarSystemModel->findById($userId);
        
            if (empty($availYears)) {
                $availYears = [(int) date('Y')];
            }
        
            if (isset($_GET['year']) && in_array((int) $_GET['year'], $availYears)) {
                $selectedYear = (int) $_GET['year'];
            } else {
                $selectedYear = (int) $availYears[0];
            }
        
            $lat = 6.9271;
            $lon = 79.8612;
        
            require_once APPROOT . '/api/weather_api.php';
            $daily_forecast = getDailySolarForecast($lat, $lon);
        
            $data = [
                'user'            => $this->user,
                'stats'           => $stats,
                'chart_data'      => $this->smsModel->get_chart_data($userId, 12, $selectedYear),
                'selected_year'   => $selectedYear,
                'available_years' => $availYears,
                'daily_forecast'  => $daily_forecast
            ];
        
            $this->view('pages/homeowner/dashboard', $data, layout: 'dashboard');
        
        } else if ($page == 'uploadsms') { 
        
            $data = [
                'user' => $this->user,
            ];
        
            $this->uploadSMS();
        }
    }
        
    // --- PayHere Checkout ---
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/homeowner/shop/cart');
            exit();
        }

        $cartJson = $_POST['cart'] ?? '[]';
        $cart     = json_decode($cartJson, true);

        if (empty($cart)) {
            header('Location: ' . URLROOT . '/homeowner/shop/cart');
            exit();
        }

        // Calculate total
        $amount = 0;
        foreach ($cart as $item) {
            $amount += (float)($item['price'] ?? 0) * (int)($item['qty'] ?? 1);
        }
        $amount   = round($amount, 2);
        $currency = 'LKR';

        // Save order to DB first — use its real integer ID as the PayHere order_id
        $orderId = $this->inventoryModel->create_order([
            'user_id'      => 1, // replace with session user id when auth is added
            'total_amount' => $amount,
            'status'       => 'pending',
            'date'         => date('Y-m-d'),
        ], $cart);

        // Generate PayHere hash
        // hash = MD5(merchant_id + order_id + amount + currency + MD5(secret).toUpperCase())
        $merchantId     = PAYHERE_MERCHANT_ID;
        $merchantSecret = PAYHERE_MERCHANT_SECRET;
        $secretHash     = strtoupper(md5($merchantSecret));
        $hash           = strtoupper(md5($merchantId . $orderId . number_format($amount, 2, '.', '') . $currency . $secretHash));

        $payhereUrl = PAYHERE_SANDBOX
            ? 'https://sandbox.payhere.lk/pay/checkout'
            : 'https://www.payhere.lk/pay/checkout';

        $data = [
            'user'         => $this->user,
            'payhere_url'  => $payhereUrl,
            'merchant_id'  => $merchantId,
            'order_id'     => $orderId,
            'amount'       => number_format($amount, 2, '.', ''),
            'currency'     => $currency,
            'hash'         => $hash,
            'return_url'   => URLROOT . '/homeowner/paymentReturn',
            'cancel_url'   => URLROOT . '/homeowner/paymentCancel',
            'notify_url'   => URLROOT . '/homeowner/paymentNotify',
            'cart'         => $cart,
        ];

        $this->view('pages/homeowner/checkout', $data, layout: 'dashboard');
    }

    public function paymentReturn() {
        $data = ['user' => $this->user, 'status' => 'success'];
        $this->view('pages/homeowner/payment_result', $data, layout: 'dashboard');
    }

    public function paymentCancel() {
        $data = ['user' => $this->user, 'status' => 'cancelled'];
        $this->view('pages/homeowner/payment_result', $data, layout: 'dashboard');
    }

    public function paymentNotify() {
        // Verify PayHere notification
        $merchantId     = PAYHERE_MERCHANT_ID;
        $merchantSecret = PAYHERE_MERCHANT_SECRET;

        $orderId        = $_POST['order_id']        ?? '';
        $paymentId      = $_POST['payment_id']      ?? '';
        $payhereAmount  = $_POST['payhere_amount']  ?? '';
        $payhereCurrency= $_POST['payhere_currency']?? '';
        $statusCode     = $_POST['status_code']     ?? '';
        $md5sig         = $_POST['md5sig']          ?? '';

        $secretHash     = strtoupper(md5($merchantSecret));
        $localHash      = strtoupper(md5($merchantId . $orderId . $payhereAmount . $payhereCurrency . $statusCode . $secretHash));

        if ($localHash === $md5sig && $statusCode == 2) {
            // Payment successful — update order status
            $this->inventoryModel->update_order_status($orderId, 'completed');
        }
        http_response_code(200);
        exit();
    }

    public function service(): void
    {
        $history = $this->serviceModel->get_service_history();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'user' => $this->user,
                'service_type' => trim($_POST['service_type'] ?? ''),
                'service_description' => trim($_POST['service_description'] ?? ''),
                'serviceHistory' => $history,
                'service_type_err' => '',
                'service_description_err' => ''
            ];

            // Validation
            if (empty($data['service_type'])) {
                $data['service_type_err'] = "Please select a service type";
            }
            if (empty($data['service_description'])) {
                $data['service_description_err'] = "Please describe the issue";
            }

            if (!empty($data['service_type_err']) || !empty($data['service_description_err'])) {
                $this->view('pages/homeowner/service', $data, layout: 'dashboard');
                return;
            }

            // // Safe user_id extraction
            // $userId = $this->user['user_id'] ?? null;
            // if (empty($userId)) {
            //     setToast('User not authenticated.', 'error');
            //     redirect('login');
            //     return;
            // }

            $modelData = [  // pass scalar ID only
                'service_type' => $data['service_type'],
                'service_description' => $data['service_description']
            ];


            if ($this->serviceModel->add_service_request($modelData)) {
                setToast('Service request submitted successfully!', 'success');
                redirect('homeowner/service');
            } else {
                setToast('Failed to submit request. Try again.', 'error');
                $this->view('pages/homeowner/service', $data, layout: 'dashboard');
            }
        } else {
            $data = [
                'user' => $this->user,
                'service_type' => '',
                'service_description' => '',
                'serviceHistory' => $history,
                'service_type_err' => '',
                'service_description_err' => ''
            ];
            $this->view('pages/homeowner/service', $data, layout: 'dashboard');
        }
    }

    public function shop($page = 'sudu')
    {

        if ($page == 'sudu') {

            $data = [
                'user' => $this->user,
                'products' => $this->getProducts()
            ];


            $this->view('pages/homeowner/shop', $data, 'dashboard');

        } else if ($page = 'cart') {
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/cart', $data, 'dashboard');
        }

    }

    public function profile()
    {
        $user_data = $this->profileModel->getHomeownerProfile($_SESSION['user_id']);
        $data = [
            'user' => $this->user,
            'user_data' => $user_data
        ];

        $this->view('pages/homeowner/profile', $data, 'dashboard');
    }

    public function help()
    {
        $data = [
            'user' => $this->user,
        ];
        $this->view('pages/homeowner/help', $data, 'dashboard');
    }

    public function reports()
    {
        $data = [
            'user' => $this->user,
        ];
        $this->view('pages/homeowner/reports', $data, 'dashboard');
    }


    public function uploadSMS(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sms = trim($_POST['smsContent'] ?? '');

            if (empty($sms)) {
                setToast('SMS content is missing.', 'error');
                redirect('homeowner/dashboard/uploadsms');
                return;
            }

            $parsed = $this->smsModel->parse_sms($sms);

            if (!$parsed) {
                setToast('Invalid CEB SMS format. Please paste a valid bill SMS.', 'error');
                redirect('homeowner/dashboard/uploadsms');
                return;
            }

            $userId = $_SESSION['user_id'];

            // Duplicate check — same user + reading_date already in DB
            if ($this->smsModel->check_duplicate_reading_date($userId, $parsed['reading_date'])) {
                setToast('This SMS has already been uploaded (reading date: ' . $parsed['reading_date'] . ').', 'error');
                redirect('homeowner/dashboard/uploadsms');
                return;
            }

            $parsed['user_id']    = $userId;
            $parsed['created_at'] = date('Y-m-d H:i:s');
            $parsed['raw_sms']    = $sms;
            $parsed['expected_generation'] = $this->calculateExpectedGeneration($parsed['reading_date']);
            
            if ($this->smsModel->upload_sms($parsed)) {
                setToast('SMS uploaded successfully!', 'success');
                redirect('homeowner/dashboard/uploadsms');
            } else {
                setToast('Failed to save SMS. Please try again.', 'error');
                redirect('homeowner/dashboard/uploadsms');
            }
        } else {
            // GET — show the upload form
            $data = [
                'user' => $this->user,
                'recentUploads' => $this->smsModel->sms_history()
            ];
            $this->view('pages/homeowner/uploadsms', $data, 'dashboard');
        }
    }



    public function saveSMS()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Just return success for UI demo
            echo json_encode(['success' => true]);
        }
    }

    public function productDetails($id = null)
    {
        if ($id === null) {
            header('Location: ' . URLROOT . '/homeowner/shop');
            exit();
        }

        $products = $this->getProducts();
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            header('Location: ' . URLROOT . '/homeowner/shop');
            exit();
        }

        $data = [
            'user' => $this->user,
            'product' => $product
        ];

        $this->view('pages/homeowner/product_details', $data, 'dashboard');
    }

    private function getProducts()
    {
        return [
            [
                'id' => 1,
                'title' => 'Premium Solar Battery',
                'company' => 'SolarTech Solutions',
                'price' => 899.99,
                'description' => 'High-capacity lithium battery perfect for residential solar systems. 10-year warranty included.',
                'image' => 'solar_battery.png',
                'category' => 'batteries'
            ],
            [
                'id' => 2,
                'title' => 'Solar Panel Kit',
                'company' => 'EcoEnergy Systems',
                'price' => 1299.99,
                'description' => 'Complete solar panel kit with mounting hardware. Perfect for residential installation.',
                'image' => 'solar_panel_kit.png',
                'category' => 'panels'
            ],
            [
                'id' => 3,
                'title' => 'Solar Garden Lamp Set',
                'company' => 'GreenLight Solutions',
                'price' => 129.99,
                'description' => 'Set of 4 solar-powered garden lamps with motion sensors and dusk-to-dawn operation.',
                'image' => 'solar_graden_lamp_set.png',
                'category' => 'lighting'
            ],
            [
                'id' => 4,
                'title' => 'Smart Solar Inverter',
                'company' => 'PowerTech Pro',
                'price' => 799.99,
                'description' => 'Smart inverter with WiFi monitoring capabilities and automatic power management.',
                'image' => 'smart_solar_inverter.png',
                'category' => 'inverters'
            ],
            [
                'id' => 5,
                'title' => 'Portable Solar Power Bank',
                'company' => 'MobilePower Plus',
                'price' => 59.99,
                'description' => '20000mAh solar-powered power bank with dual USB ports and fast charging capability.',
                'image' => 'portable_solar_powerbank.png',
                'category' => 'gadgets'
            ],
            [
                'id' => 6,
                'title' => 'Solar Powered Fan',
                'company' => 'CoolBreeze Solar',
                'price' => 149.99,
                'description' => 'Energy-efficient solar fan with remote control and built-in battery backup.',
                'image' => 'fan.png',
                'category' => 'gadgets'
            ],
            [
                'id' => 7,
                'title' => 'Solar Water Heater',
                'company' => 'HotWater Solutions',
                'price' => 699.99,
                'description' => '200L solar water heater with intelligent temperature control and backup heating.',
                'image' => 'solar_water_heater.jpg',
                'category' => 'heaters'
            ],
            [
                'id' => 8,
                'title' => 'Solar Security Camera',
                'company' => 'SecureVision',
                'price' => 199.99,
                'description' => 'Wireless security camera with solar charging, night vision, and mobile app control.',
                'image' => 'solar_camera.jpg',
                'category' => 'security'
            ],

        ];
    }

    // --- Notifications ---
    public function notifications()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    private function calculateExpectedGeneration(string $readingDate): ?float
    {
        require_once APPROOT . '/api/generation_api.php';

        $userId = $_SESSION['user_id'];
        $system = $this->solarSystemModel->get_by_user($userId);

        if (!$system) {
            return null; // no solar system on record for this user
        }

        $month    = (int) date('n', strtotime($readingDate));
        $district = DISTRICTS[$system->district] ?? DISTRICTS[COLOMBO];

        $result = getSolarGenerationByMonth(
            month:          $month,
            systemCapacity: (float) $system->capacity,
            moduleType:     (int)   $system->module_type,
            losses:         (float) $system->losses_pct,
            arrayType:      (int)   $system->array_type,
            tilt:           (float) $system->tilt,
            azimuth:        (float) $system->azimuth,
            lat:            $district['lat'],
            lon:            $district['lon'],
            apiKey:         NREL_API_KEY
        );

        return $result['success'] ? round($result['generation_kwh'], 2) : null;
    }
}

?>