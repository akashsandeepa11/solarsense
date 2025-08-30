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
            
            $this->view('pages/admin/dashboard', $data, layout: 'dashboard');
        }

        public function add_customer(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
        }
        
        public function user_management(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/admin/user_management', $data, layout: 'dashboard');
        }
        
        public function platform_reports(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/admin/reports', $data, layout: 'dashboard');
        }

        public function system_settings(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/admin/settings', $data, layout: 'dashboard');
        }

        // --- Inherited Installer Pages (for viewing/managing) ---

        public function fleet_management(){
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
