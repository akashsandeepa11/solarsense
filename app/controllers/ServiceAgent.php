<?php

    class ServiceAgent extends Controller{


        private $user = [
            'role' => ROLE_SERVICE_AGENT,
        ];

        public function __construct(){
            die('ServiceAgent controller loaded');
        }

        public function tasks(): void{

            echo "dfghjkl;";
            
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/tasks', $data, 'dashboard');
        }

        public function history(){
            
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/history', $data, 'dashboard');
        }

        public function profile(){
            
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/profile', $data, 'dashboard');
        }

    }

?>