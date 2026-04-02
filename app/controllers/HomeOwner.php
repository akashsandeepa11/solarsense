<?php
class HomeOwner extends Controller
{
    private $serviceModel;
    private $smsModel;
    private $dashboardModel;
    private $profileModel;

    private $generationModel;
    private $systemModel;

    private $user = [
        'role' => ROLE_HOMEOWNER,
    ];


    public function __construct()
    {
        $this->serviceModel = $this->model('M_Service');
        $this->smsModel = $this->model('M_SMS');
        $this->dashboardModel = $this->model('M_Homeowner_Dashboard');
        $this->profileModel = $this->model('M_Profile');
        $this->generationModel = $this->model('M_Solargeneration');
        $this->systemModel = $this->model('M_Solarsystem');

    }


    public function dashboard($page = 'index')
    {
        if ($page == 'index') {
            $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
            $stats = $this->dashboardModel->getStats($_SESSION['user_id']);
            $monthly = $this->dashboardModel->getMonthlyGeneration($_SESSION['user_id'], $year);
            $system = $this->systemModel->findById($_SESSION['user_id']);
            $systemId = $system ? (int) $system['system_id'] : null;

            // Extract the 12 monthly expected values, defaulting to 0 if not yet calculated

            $expectedMonthly = array_fill(0, 12, 0);

            if ($systemId !== null) {
                $estimate = $this->generationModel->findLatestBySystem($systemId);
            }

            $data['expected_generation'] = $expectedMonthly;


            // Prepare chart arrays
            $labels = [];
            $generation = [];
            foreach ($monthly as $row) {
                $key = $year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT); // e.g. "2025-03"
                $generation[$key] = (int) $row->generation;
            }

            $data = [
                'user' => $this->user,
                'stats' => $stats,
                'chart_labels' => $labels,
                'chart_generation' => $generation,
                'selected_year' => $year,
                'expected_generation' => $data['expected_generation']
            ];

            $this->view('pages/homeowner/dashboard', $data, layout: 'dashboard');

        } else if ($page == 'uploadsms') {
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/uploadsms', $data, 'dashboard');
        }
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
                die('SMS content missing');
            }

            $data = $this->smsModel->parse_sms($sms);

            if (!$data) {
                die('Invalid CEB SMS format');
            }

            $data['user_id'] = $_SESSION['user_id'];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['raw_sms'] = $sms;

            if ($this->smsModel->upload_sms($data)) {
                redirect('homeowner/uploadsms');
            } else {
                die('Failed to save SMS');
            }
        } else {
            // For GET requests, show the form
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
}

?>