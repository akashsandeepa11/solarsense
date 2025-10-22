<?php

    class SuperAdmin extends Controller{

        private $user = [
            'role' => ROLE_SUPER_ADMIN,
        ];

        public function __construct(){

        }

        public function dashboard(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/super_admin/dashboard', $data, layout: 'dashboard');
        }

        public function companies(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
        }

        public function verification(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/verification', $data, 'dashboard');
        }

        public function complaints(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/complaints', $data, 'dashboard');
        }

        public function reports(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/reports', $data, 'dashboard');
        }
        
        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/profile', $data, 'dashboard');
        }
    }

?>
