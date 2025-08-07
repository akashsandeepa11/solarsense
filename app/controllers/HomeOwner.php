<?php

    class HomeOwner extends Controller{
        public function __construct(){

        }

        public function dashboard(){

            $data = [];

            $this->view('pages/homeowner/dashboard', $data);
        }

        public function profile(){

            $data = [];

            $this->view('pages/homeowner/profile', $data);
        }
    }

?>
