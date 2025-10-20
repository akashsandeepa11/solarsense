<?php

    class InventoryManager extends Controller{

        private $user = [
            'role' => ROLE_INVENTORY_MANAGER,
        ];

        public function __construct(){
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // --- Admin-Specific Pages ---

        public function inventory(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/inventory_manager/inventory', $data, layout: 'dashboard');
        }

        public function suppliers(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/inventory_manager/suppliers', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/inventory_manager/profile', $data, layout: 'dashboard');
        }

        public function help(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/inventory_manager/help', $data, 'dashboard');
        }
        
        
    }

?>
