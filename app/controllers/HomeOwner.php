<?php

    class HomeOwner extends Controller{

        private $user = [
            'role' => ROLE_HOMEOWNER,
        ];

        private $inventoryModel;

        public function __construct(){
            $this->inventoryModel = $this->model('M_inventory');
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
    }

?>
