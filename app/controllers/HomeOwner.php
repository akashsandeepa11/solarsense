<?php

    class HomeOwner extends Controller{

        private $user = [
            'role' => ROLE_HOMEOWNER,
        ];

        public function __construct(){

        }

        public function dashboard($page='index'){

            if($page=='index'){
                $data = [
                    'user' => $this->user,
                ];
            
                $this->view('pages/homeowner/dashboard', $data, layout: 'dashboard');
            }
            else if($page='uploadsms'){
                $data = [
                            'user' => $this->user,
                        ];
                        
                        $this->view('pages/homeowner/uploadsms', $data, 'dashboard');
            }
        }

        public function service(){
            
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/homeowner/service', $data, 'dashboard');
        }
        
        public function shop($page='sudu'){

                    if($page=='sudu'){

                        $data = [
                            'user' => $this->user,
                            'products' => $this->getProducts()
                        ];
                        
                        
                        $this->view('pages/homeowner/shop', $data, 'dashboard');
                        
                    }else if($page='cart'){
                        $data = [
                            'user' => $this->user,
                        ];
                        
                        $this->view('pages/homeowner/cart', $data, 'dashboard');
                    }

                }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/profile', $data, 'dashboard');
        }

        public function help(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/help', $data, 'dashboard');
        }

        public function saveSMS() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Just return success for UI demo
                echo json_encode(['success' => true]);
            }
        }

        public function productDetails($id = null) {
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

        private function getProducts() {
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
                    'image' => 'solar_battery.png',
                    'category' => 'heaters'
                ],
                [
                    'id' => 8,
                    'title' => 'Solar Security Camera',
                    'company' => 'SecureVision',
                    'price' => 199.99,
                    'description' => 'Wireless security camera with solar charging, night vision, and mobile app control.',
                    'image' => 'solar_battery.png',
                    'category' => 'security'
                ],
                [
                    'id' => 9,
                    'title' => 'Solar System Controller',
                    'company' => 'SmartControl Tech',
                    'price' => 299.99,
                    'description' => 'Smart solar system controller with LCD display and mobile app integration.',
                    'image' => 'solar_battery.png',
                    'category' => 'inverters'
                ],
                [
                    'id' => 10,
                    'title' => 'Solar Pool Pump',
                    'company' => 'AquaSolar',
                    'price' => 449.99,
                    'description' => 'Energy-efficient solar pool pump with variable speed control and timer function.',
                    'image' => 'solar_battery.png',
                    'category' => 'accessories'
                ],
                [
                    'id' => 11,
                    'title' => 'Solar String Lights',
                    'company' => 'FairyGlow',
                    'price' => 39.99,
                    'description' => '100 LED waterproof string lights with 8 lighting modes and auto on/off.',
                    'image' => 'solar_battery.png',
                    'category' => 'lighting'
                ],
                [
                    'id' => 12,
                    'title' => 'MPPT Charge Controller',
                    'company' => 'PowerMax Systems',
                    'price' => 249.99,
                    'description' => '60A MPPT solar charge controller with advanced battery protection and monitoring.',
                    'image' => 'solar_battery.png',
                    'category' => 'inverters'
                ]
            ];
        }
    }

?>
