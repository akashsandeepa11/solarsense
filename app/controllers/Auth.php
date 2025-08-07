<?php

    class Auth extends Controller{
        public function __construct(){

        }
        
        public function login(){
            $data=[];

            $this->view('pages/auth/v_login', $data);
        }

        public function register(){
            $data = ['name'=>'akash'];
            $this->view('pages/auth/v_register', $data);
        }
    }

?>