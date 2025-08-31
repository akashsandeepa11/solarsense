<?php

    class HomeOwner extends Controller{

        private $user = [
            'role' => ROLE_HOMEOWNER,
        ];

        public function __construct(){

        }

        public function dashboard(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/homeowner/dashboard', $data, layout: 'dashboard');
        }
        
        public function service(){
            
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/homeowner/service', $data, 'dashboard');
        }
        
        public function shop(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/shop', $data, 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/profile', $data, 'dashboard');
        }

        public function profile_2(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/homeowner/profile_2', $data, 'dashboard');
        }
    }

?>
