<?php

    class Users extends Controller{
        public function __construct(){

        }
        
        public function login(){
            $data=[];

            $this->view('pages/users/v_login', $data);
        }

        public function register(){
            $data = ['name'=>'akash'];
            $this->view('pages/users/v_register', $data);
        }
    }

?>