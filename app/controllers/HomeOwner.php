<?php

class HomeOwner extends Controller
{

    private $smsModel;

    private $user = [
        'role' => ROLE_HOMEOWNER,
    ];

    public function __construct()
    {

        $this->smsModel = $this->model('M_SMS');

    }

    public function dashboard($page = 'index')
    {

        if ($page == 'index') {
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/dashboard', $data, layout: 'dashboard');
        } else if ($page = 'uploadsms') {
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/uploadsms', $data, 'dashboard');
        }
    }

    public function service()
    {

        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/homeowner/service', $data, 'dashboard');
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
        $data = [
            'user' => $this->user,
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

    public function uploadSMS()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('homeowner/dashboard');
        }

        $sms = trim($_POST['smsContent'] ?? '');

        if (empty($sms)) {
            die('SMS content missing');
        }

        // ---------- PARSE SMS ----------
        $parsed = $this->parseSMS($sms);

        if (!$parsed) {
            die('Invalid CEB SMS format');
        }

        $parsed['user_id'] = $_SESSION['user_id']; // adjust to your auth system
        $parsed['raw_sms'] = $sms;

        // ---------- SAVE ----------
        if ($this->smsModel->uploadSMS($parsed)) {
            redirect('homeowner/uploadSms');
        } else {
            die('Failed to save SMS');
        }
    }

    private function parseSMS($sms)
    {
        $data = [];

        // Account number + type
        if (preg_match('/A\/C No:\s*(\d+)\s*\((.*?)\)/', $sms, $m)) {
            $data['account_no'] = $m[1];
            $data['account_type'] = $m[2];
        }

        // Customer name (next line after A/C)
        if (preg_match('/\)\s*\n([A-Z\.\s]+)/', $sms, $m)) {
            $data['customer_name'] = trim($m[1]);
        }

        // Reading date
        if (preg_match('/Reading Date:\s*(\d{4}-\d{2}-\d{2})/', $sms, $m)) {
            $data['reading_date'] = $m[1];
        }

        // Consumption
        if (preg_match('/Consumption:\s*(\d+)\s*Unit/', $sms, $m)) {
            $data['consumption_units'] = (int) $m[1];
        }

        // Readings (Export / Import)
        if (preg_match('/Readings:\s*(\d+)\(E\),\s*(\d+)\(I\)/', $sms, $m)) {
            $data['export_reading'] = (int) $m[1];
            $data['import_reading'] = (int) $m[2];
        }

        // Previous readings
        if (preg_match('/Prv\. Readings:\s*(\d+)\(E\),\s*(\d+)\(I\)/', $sms, $m)) {
            $data['prev_export_reading'] = (int) $m[1];
            $data['prev_import_reading'] = (int) $m[2];
        }

        // Monthly bill
        if (preg_match('/Monthly Bill:\s*Rs\.\s*([-\d,]+\.\d{2})/', $sms, $m)) {
            $data['monthly_bill'] = floatval(str_replace(',', '', $m[1]));
        }

        // Total due
        if (preg_match('/Total Due:\s*Rs\.\s*([-\d,]+\.\d{2})/', $sms, $m)) {
            $data['total_due'] = floatval(str_replace(',', '', $m[1]));
        }

        return $data;
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