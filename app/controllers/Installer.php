<?php

    class Installer extends Controller{

        private $user = [
            'role' => ROLE_INSTALLER,
        ];

        public function __construct(){

        }

        public function dashboard(){

            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer/dashboard', $data, 'dashboard');
        }

        public function fleet_dashboard(){

            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer/fleet_dashboard', $data, 'dashboard');
        }

        public function maintenance(){

            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer/maintenance', $data, 'dashboard');
        }

        public function profile(){

            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer/profile', $data, 'dashboard');
        }
    }

?>
