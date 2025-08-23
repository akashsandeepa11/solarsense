<?php

    class Admin extends Controller{

        private $user = [
            'role' => ROLE_ADMIN,
        ];

        public function __construct(){
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // --- Admin-Specific Pages ---

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/admin/dashboard', $data, layout: 'dashboard');
        }
        
        public function userManagement(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/admin/user_management', $data, layout: 'dashboard');
        }
        
        public function platformReports(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/admin/reports', $data, layout: 'dashboard');
        }

        public function systemSettings(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/admin/settings', $data, layout: 'dashboard');
        }

        // --- Inherited Installer Pages (for viewing/managing) ---

        public function fleetManagement(){
            $data = [
                'user' => $this->user,
            ];

            // This would load a view showing ALL customers on the platform
            $this->view('pages/admin/fleet_management', $data, 'dashboard');
        }

        public function maintenance(){
            $data = [
                'user' => $this->user,
            ];

            // This would load a view showing ALL maintenance tasks
            $this->view('pages/admin/maintenance', $data, 'dashboard');
        }
    }

?>
