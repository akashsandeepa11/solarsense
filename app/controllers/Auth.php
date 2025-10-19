<?php

    class Auth extends Controller{
        public function __construct(){

        }
        
        public function login(){
            $data=[];

            $this->view('pages/auth/login', $data, layout: "main");
        }

        public function register(){
            $data = ['name'=>'akash'];
            $this->view('pages/auth/register', $data, layout: "main");
        }
    }

?>