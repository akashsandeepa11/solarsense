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
                        
                    }else if($page=='cart'){
                        $data = [
                            'user' => $this->user,
                        ];
                        
                        $this->view('pages/homeowner/cart', $data, 'dashboard');
                    }else if($page=='checkout'){
                        $this->checkout();
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
            
        public function reports(){
            $data = [
                'user' => $this->user,
            ];
            $this->view('pages/homeowner/reports', $data, 'dashboard');
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
        public function notifications(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/common/notifications', $data, layout: 'dashboard');
        }

        // --- PayHere Payment Gateway ---
        
        public function checkout() {
            require_once APPROOT . '/helpers/PayHere.php';
            
            // Get cart from session
            $cartItems = $_SESSION['cart'] ?? [];
            
            // Redirect to cart if empty
            if (empty($cartItems)) {
                header('Location: ' . URLROOT . '/homeowner/shop/cart');
                exit;
            }
            
            $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
            $itemCount = array_sum(array_column($cartItems, 'quantity'));
            $orderId = 'SS-' . time() . '-' . rand(1000, 9999);
            
            $data = [
                'user' => $this->user,
                'cartItems' => $cartItems,
                'subtotal' => $subtotal,
                'itemCount' => $itemCount,
                'orderId' => $orderId,
                'payhereUrl' => PayHere::getCheckoutUrl()
            ];
            
            $this->view('pages/homeowner/checkout', $data, 'dashboard');
        }
        
        public function paymentNotify() {
            require_once APPROOT . '/helpers/PayHere.php';
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
            
            $logFile = APPROOT . '/logs/payhere_' . date('Y-m-d') . '.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . json_encode($_POST) . "\n", FILE_APPEND);
            
            if (!PayHere::verifyCallback($_POST, PAYHERE_MERCHANT_SECRET)) {
                http_response_code(400); exit;
            }
            
            http_response_code(200);
            echo 'OK';
            exit;
        }
        
        public function paymentReturn() {
            $data = [
                'user' => $this->user,
                'orderId' => $_GET['order_id'] ?? 'N/A',
                'status' => 'success'
            ];
            $this->view('pages/homeowner/payment_success', $data, 'dashboard');
        }
        
        public function paymentCancel() {
            $data = ['user' => $this->user, 'status' => 'cancelled'];
            $this->view('pages/homeowner/payment_cancelled', $data, 'dashboard');
        }

        // --- Cart Management (Session-based) ---
        
        public function addToCart() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request']);
                exit;
            }
            
            $productId = $_POST['product_id'] ?? null;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Product ID required']);
                exit;
            }
            
            // Find product
            $products = $this->getProducts();
            $product = null;
            foreach ($products as $p) {
                if ($p['id'] == $productId) {
                    $product = $p;
                    break;
                }
            }
            
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
            
            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Add to cart or update quantity
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity']++;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'title' => $product['title'],
                    'company' => $product['company'],
                    'price' => $product['price'] * 325, // Convert to LKR
                    'image' => $product['image'],
                    'quantity' => 1
                ];
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Added to cart',
                'cartCount' => $this->getCartCount()
            ]);
            exit;
        }
        
        public function removeFromCart() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false]);
                exit;
            }
            
            $productId = $_POST['product_id'] ?? null;
            if ($productId && isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($item) use ($productId) {
                    return $item['id'] != $productId;
                }));
            }
            
            echo json_encode(['success' => true, 'cartCount' => $this->getCartCount()]);
            exit;
        }
        
        public function updateCartQty() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false]);
                exit;
            }
            
            $productId = $_POST['product_id'] ?? null;
            $quantity = max(1, intval($_POST['quantity'] ?? 1));
            
            if ($productId && isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $productId) {
                        $item['quantity'] = $quantity;
                        break;
                    }
                }
            }
            
            echo json_encode(['success' => true, 'cartCount' => $this->getCartCount()]);
            exit;
        }
        
        public function getCartData() {
            echo json_encode([
                'success' => true,
                'cart' => $_SESSION['cart'] ?? [],
                'cartCount' => $this->getCartCount()
            ]);
            exit;
        }
        
        private function getCartCount() {
            if (!isset($_SESSION['cart'])) return 0;
            return array_sum(array_column($_SESSION['cart'], 'quantity'));
        }
        
        public function clearCart() {
            $_SESSION['cart'] = [];
            echo json_encode(['success' => true]);
            exit;
        }
    }

?>
