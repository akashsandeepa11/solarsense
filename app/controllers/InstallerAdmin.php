<?php

    class InstallerAdmin extends Controller{

        private $user = [
            'role' => ROLE_INSTALLER_ADMIN,
        ];

        public function __construct(){
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // --- Admin-Specific Pages ---

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/dashboard', $data, layout: 'dashboard');
        }

        public function add_customer(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
        }

        public function fleet(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/fleet_dashboard', $data, layout: 'dashboard');
        }

        public function team(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/team', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/profile', $data, layout: 'dashboard');
        }
        
        
    }

?>
