<?php

    class ServiceAgent extends Controller{

        private $user = [
            'role' => ROLE_SERVICE_AGENT,
        ];

        public function __construct(){

        }

        public function tasks(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/service_agent/task', $data, layout: 'dashboard');
        }

        public function history(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/service_agent/history', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/profile', $data, 'dashboard');
        }
    }

?>
