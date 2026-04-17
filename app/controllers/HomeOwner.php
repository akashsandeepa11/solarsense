<?php
class HomeOwner extends Controller
{
    private $serviceModel;
    private $smsModel;
    private $solarSystemModel;
    private $dashboardModel;
    private $inventoryModel;
    private $profileModel;
    private $notificationModel;

    

    private $user = [
        'role' => ROLE_HOMEOWNER,
    ];

    public function __construct()
    {
        $this->serviceModel       = $this->model('M_Service');
        $this->smsModel           = $this->model('M_SMS');
        $this->solarSystemModel   = $this->model('M_SolarSystem');
        $this->dashboardModel     = $this->model('M_Homeowner_Dashboard');
        $this->inventoryModel     = $this->model('M_inventory');
        $this->profileModel       = $this->model('M_Profile');
        $this->notificationModel  = $this->model('M_Notification');
    }


    public function dashboard($page = 'index')
    {
        if ($page == 'index') {

            $userId = (int) $_SESSION['user_id'];
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

            $notifConfig = $this->getNotificationConfig();

            $data = [
                'user'             => $this->user,
                'stats'            => $stats,
                'chart_data'       => $this->smsModel->get_chart_data($userId, 12, $selectedYear),
                'selected_year'    => $selectedYear,
                'available_years'  => $availYears,
                'daily_forecast'   => $daily_forecast,
                'notifications'    => $notifConfig['notifications'],
                'notif_badge'      => $notifConfig['badge_count'],
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
    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/homeowner/shop/cart');
            exit();
        }

        $cartJson = $_POST['cart'] ?? '[]';
        $cart = json_decode($cartJson, true);

        if (empty($cart)) {
            header('Location: ' . URLROOT . '/homeowner/shop/cart');
            exit();
        }

        // 1. Get the actual user ID from the session
        $userId = $_SESSION['user_id'];

        // Calculate total
        $amount = 0;
        foreach ($cart as $item) {
            $amount += (float) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 1);
        }
        $amount = round($amount, 2);
        $currency = 'LKR';

        // 2. Pass the real $userId to the model instead of hardcoded '1'
        $orderId = $this->inventoryModel->create_order([
            'user_id' => $userId,
            'total_amount' => $amount,
            'status' => 'pending',
            'date' => date('Y-m-d'),
        ], $cart);

        // Generate PayHere hash
        // hash = MD5(merchant_id + order_id + amount + currency + MD5(secret).toUpperCase())
        $merchantId = PAYHERE_MERCHANT_ID;
        $merchantSecret = PAYHERE_MERCHANT_SECRET;
        $secretHash = strtoupper(md5($merchantSecret));
        $hash = strtoupper(md5($merchantId . $orderId . number_format($amount, 2, '.', '') . $currency . $secretHash));

        $payhereUrl = PAYHERE_SANDBOX
            ? 'https://sandbox.payhere.lk/pay/checkout'
            : 'https://www.payhere.lk/pay/checkout';

        $data = [
            'user' => $this->user,
            'payhere_url' => $payhereUrl,
            'merchant_id' => $merchantId,
            'order_id' => $orderId,
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $currency,
            'hash' => $hash,
            'return_url' => URLROOT . '/homeowner/paymentReturn',
            'cancel_url' => URLROOT . '/homeowner/paymentCancel',
            'notify_url' => URLROOT . '/homeowner/paymentNotify',
            'cart' => $cart,
        ];

        $this->view('pages/homeowner/checkout', $data, layout: 'dashboard');
    }

    public function paymentReturn()
    {
        $data = ['user' => $this->user, 'status' => 'success'];
        $this->view('pages/homeowner/payment_result', $data, layout: 'dashboard');
    }

    public function paymentCancel()
    {
        $data = ['user' => $this->user, 'status' => 'cancelled'];
        $this->view('pages/homeowner/payment_result', $data, layout: 'dashboard');
    }

    public function paymentNotify()
    {
        // Verify PayHere notification
        $merchantId = PAYHERE_MERCHANT_ID;
        $merchantSecret = PAYHERE_MERCHANT_SECRET;

        $orderId = $_POST['order_id'] ?? '';
        $paymentId = $_POST['payment_id'] ?? '';
        $payhereAmount = $_POST['payhere_amount'] ?? '';
        $payhereCurrency = $_POST['payhere_currency'] ?? '';
        $statusCode = $_POST['status_code'] ?? '';
        $md5sig = $_POST['md5sig'] ?? '';

        $secretHash = strtoupper(md5($merchantSecret));
        $localHash = strtoupper(md5($merchantId . $orderId . $payhereAmount . $payhereCurrency . $statusCode . $secretHash));

        if ($localHash === $md5sig && $statusCode == 2) {
            // Payment successful — update order status
            $this->inventoryModel->update_order_status($orderId, 'completed');
        }
        http_response_code(200);
        exit();
    }

    // public function service(): void
    // {
    //     $history = $this->serviceModel->get_service_history();

    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

    //         $data = [
    //             'user' => $this->user,
    //             'service_type' => trim($_POST['service_type'] ?? ''),
    //             'service_description' => trim($_POST['service_description'] ?? ''),
    //             'serviceHistory' => $history,
    //             'service_type_err' => '',
    //             'service_description_err' => ''
    //         ];

    //         // Validation
    //         if (empty($data['service_type'])) {
    //             $data['service_type_err'] = "Please select a service type";
    //         }
    //         if (empty($data['service_description'])) {
    //             $data['service_description_err'] = "Please describe the issue";
    //         }

    //         if (!empty($data['service_type_err']) || !empty($data['service_description_err'])) {
    //             $this->view('pages/homeowner/service', $data, layout: 'dashboard');
    //             return;
    //         }

    //         // // Safe user_id extraction
    //         // $userId = $this->user['user_id'] ?? null;
    //         // if (empty($userId)) {
    //         //     setToast('User not authenticated.', 'error');
    //         //     redirect('login');
    //         //     return;
    //         // }

    //         $modelData = [  // pass scalar ID only
    //             'service_type' => $data['service_type'],
    //             'service_description' => $data['service_description']
    //         ];

  public function service(): void
{
    $serviceTypes = $this->serviceModel->get_service_types();
    $history = $this->serviceModel->get_service_history();


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
            'user' => $this->user,
            'serviceTypes' => $serviceTypes,
            'serviceHistory' => $history,
            'service_type' => trim($_POST['service_type'] ?? ''),
            'service_description' => trim($_POST['service_description'] ?? ''),
            'service_type_err' => '',
            'service_description_err' => ''
        ];

        // validation
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

        $modelData = [
            'service_type_id' => $data['service_type'],
            'service_description' => $data['service_description']
        ];

        if ($this->serviceModel->add_service_request($modelData)) {
            setToast('Request submitted successfully!', 'success');
            redirect('homeowner/service');
        } else {
            setToast('Failed to submit request', 'error');
            $this->view('pages/homeowner/service', $data, layout: 'dashboard');
        }

        } else {
        
            $data = [
                'user' => $this->user,
                'serviceTypes' => $serviceTypes,
                 'serviceHistory' => $history,
                'service_type' => '',
                'service_description' => '',
                'service_type_err' => '',
                'service_description_err' => ''
            ];
        
            $this->view('pages/homeowner/service', $data, layout: 'dashboard');
        }
    }

    public function shop($page = 'index')
    {
        if ($page == 'index') {
            $user_id = $_SESSION['user_id'];

            // FIX: Use a new model method to get the company linked to the homeowner
            $company_id = $this->inventoryModel->getCompanyIdForHomeowner($user_id);

            // FIX: Call the local private method (remove 'inventoryModel->')
            $products = $this->getProducts($company_id);
            $categories = $this->inventoryModel->get_categories();

            $data = [
                'user' => $this->user,
                'products' => $products ?? [],
                'categories' => $categories ?? [],
            ];

            $this->view('pages/homeowner/shop', $data, 'dashboard');

        } else if ($page == 'cart') {
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

        $this->calculateExpectedGeneration('2025-02-05');

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

            $parsed['user_id'] = $userId;
            $parsed['created_at'] = date('Y-m-d H:i:s');
            $parsed['raw_sms'] = $sms;
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

        // 1. Get the correct company ID for the logged-in homeowner
        $userId = $_SESSION['user_id'];
        $companyId = $this->inventoryModel->getCompanyIdForHomeowner($userId);

        // 2. Pass the companyId to fix the ArgumentCountError
        $products = $this->getProducts($companyId);

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

    private function getProducts($company_id)
    {
        if (!$company_id)
            return [];

        $rows = $this->inventoryModel->get_all_items($company_id);

        if (empty($rows)) {
            return [];
        }

        $products = [];
        foreach ($rows as $row) {
            // ... (rest of your existing mapping logic remains the same)
            if (is_object($row)) {
                $products[] = [
                    'id' => $row->inventory_id,
                    'title' => $row->item_name,
                    'company' => '',
                    'price' => (float) $row->unit_price,
                    'description' => $row->description ?? '',
                    'image' => $row->item_image ?? '',
                    'category' => $row->category_name ?? '',
                ];
            }
        }
        return $products;
    }
    // --- Notifications ---
    public function notifications()
    {
        $notifConfig = $this->getNotificationConfig();

        $data = [
            'user'             => $this->user,
            'notifications'    => $this->notificationModel->get_all_notifications((int) $_SESSION['user_id']),
            'notif_badge'      => $notifConfig['badge_count'],
            'unread_count'     => $notifConfig['badge_count'],
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    /**
     * AJAX — Mark a single notification as read.
     * POST: notification_id
     */
    public function markNotificationRead()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit();
        }
        $notifId = (int) ($_POST['notification_id'] ?? 0);
        $userId  = (int) $_SESSION['user_id'];
        if ($notifId < 1) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit();
        }
        $ok = $this->notificationModel->mark_as_read($notifId, $userId);
        echo json_encode([
            'success'     => $ok,
            'badge_count' => $this->notificationModel->get_unread_count($userId),
        ]);
        exit();
    }

    /**
     * AJAX — Clear all notifications for the logged-in user.
     * POST (no body required)
     */
    public function clearNotifications()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit();
        }
        $userId = (int) $_SESSION['user_id'];
        $ok     = $this->notificationModel->delete_all($userId);
        echo json_encode(['success' => $ok]);
        exit();
    }

    /**
     * Build the notification config array used by topnavbar and dashboard data.
     *
     * @return array  ['notifications' => [...], 'badge_count' => int]
     */
    private function getNotificationConfig(): array
    {
        $userId        = (int) $_SESSION['user_id'];
        $notifications = $this->notificationModel->get_notifications($userId, 10);
        $badgeCount    = $this->notificationModel->get_unread_count($userId);

        return [
            'notifications' => $notifications,
            'badge_count'   => $badgeCount,
            'view_all_url'  => URLROOT . '/' . $this->user['role'] . '/notifications',
        ];
    }

    private function calculateExpectedGeneration(string $readingDate): ?float
    {
        require_once APPROOT . '/api/generation_api.php';

        $userId = $_SESSION['user_id'];
        $system = $this->solarSystemModel->findById($userId);

        if (!$system) {
            error_log("[ExpGen] FAIL: no solar_system row found for user {$userId}");
            return null;
        }

        $month = (int) date('n', strtotime($readingDate));
        $districtName = $system['district'] ?? null;



        if (empty($districtName) || !isset(DISTRICTS[$districtName])) {
            error_log("[ExpGen] FAIL: district '{$districtName}' missing or not in DISTRICTS array");
            error_log("[ExpGen] DISTRICTS keys=" . implode(', ', array_keys(DISTRICTS)));
            return null;
        }

        $coords = DISTRICTS[$districtName];
        echo "<script>console.log('" . json_encode($coords) . "');</script>";

        $result = getSolarGenerationByMonth(
            month: $month,
            systemCapacity: (float) $system['capacity'],
            moduleType: (int) $system['module_type'],
            losses: (float) $system['losses_pct'],
            arrayType: (int) $system['array_type'],
            tilt: (float) $system['tilt'],
            azimuth: (float) $system['azimuth'],
            lat: $coords['lat'],
            lon: $coords['lon'],
            apiKey: NREL_API_KEY
        );

        error_log("[ExpGen] API result=" . json_encode($result));

        return $result['success'] ? round($result['generation_kwh'], 2) : null;
    }
}

?>