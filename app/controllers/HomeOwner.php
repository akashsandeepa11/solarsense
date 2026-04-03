<?php

    class HomeOwner extends Controller{

        private $user = [
            'role' => ROLE_HOMEOWNER,
        ];

        private $inventoryModel;

        public function __construct(){
            $this->inventoryModel = $this->model('M_inventory');
        }

        // public function generation_test(){
        //     $data = [
        //         'user' => $this->user,
        //     ];
        
        //     require_once APPROOT . '/api/generation_api.php';
        
        //     $result = getSolarGenerationByMonth(
        //         month: JAN, 
        //         systemCapacity: 5,
        //         moduleType: 0,
        //         losses: 14,
        //         arrayType: 1,
        //         tilt: 10,
        //         azimuth: 180,
        //         lat: DISTRICTS[COLOMBO]['lat'],
        //         lon: DISTRICTS[COLOMBO]['lon'],
        //         apiKey: NREL_API_KEY
        //     );

        //     if ($result['success']) {
        //         echo "Month: " . $result['month_number'] . PHP_EOL;
        //         echo "Generation: " . round($result['generation_kwh'], 2) . " kWh" . PHP_EOL;
        //     } else {
        //         echo "Error: " . $result['message'] . PHP_EOL;
            
        //         if (isset($result['errors'])) {
        //             print_r($result['errors']);
        //         }
        //     }
            
        // }

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
                            'user'       => $this->user,
                            'products'   => $this->getProducts(),
                            'categories' => $this->inventoryModel->get_categories(),
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

            $item = $this->inventoryModel->get_item_by_id($id);

            if (!$item) {
                header('Location: ' . URLROOT . '/homeowner/shop');
                exit();
            }

            // Map to product shape
            $product = [
                'id'          => $item['id'],
                'title'       => $item['name'],
                'company'     => '',
                'price'       => $item['unit_price'],
                'description' => $item['description'],
                'image'       => $item['image'],
                'category'    => $item['category_name'] ?? '',
            ];

            $data = [
                'user'    => $this->user,
                'product' => $product,
            ];

            $this->view('pages/homeowner/product_details', $data, 'dashboard');
        }

        private function getProducts() {
            $rows = $this->inventoryModel->get_all_items();
            $products = [];
            foreach ((array)$rows as $row) {
                $products[] = [
                    'id'          => $row->inventory_id   ?? $row['inventory_id'],
                    'title'       => $row->item_name      ?? $row['item_name'],
                    'company'     => '',
                    'price'       => (float)($row->unit_price    ?? $row['unit_price']    ?? 0),
                    'description' => $row->description   ?? $row['description']   ?? '',
                    'image'       => $row->item_image    ?? $row['item_image']    ?? null,
                    'category'    => $row->category_name ?? $row['category_name'] ?? '',
                ];
            }
            return $products;
        }

        // --- Notifications ---
        public function notifications(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/common/notifications', $data, layout: 'dashboard');
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
    }

?>
