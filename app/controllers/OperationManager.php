<?php

    class OperationManager extends Controller{

        private $user = [
            'role' => ROLE_OPERATION_MANAGER,
        ];

        public function __construct(){
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // --- Admin-Specific Pages ---

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/dashboard', $data, layout: 'dashboard');
        }

        public function add_customer(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/add_customer', $data, layout: 'dashboard');
        }

        public function fleet(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/fleet_dashboard', $data, layout: 'dashboard');
        }

        public function quotation(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/quotation', $data, layout: 'dashboard');
        }

        public function maintenance(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/maintenance', $data, layout: 'dashboard');
        }

        public function team(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/team', $data, layout: 'dashboard');
        }

        public function reports(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/reports', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/profile', $data, layout: 'dashboard');
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
