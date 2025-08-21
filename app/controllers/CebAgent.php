<?php

    class CebAgent extends Controller{

        private $user = [
            'role' => ROLE_CEB_AGENT,
        ];

        public function __construct(){

        }

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/ceb_agent/dashboard', $data, layout:'dashboard');
        }

        public function grid_insights(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/ceb_agent/grid_insights', $data, layout:'dashboard');
        }

        public function powercut_schedule(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/ceb_agent/powercut_schedule', $data, layout:'dashboard');
        }

    }

?>