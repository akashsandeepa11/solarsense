<?php 
    class Users extends Controller{

        public function __construct(){

        }
        
        public function login(){
            $data = []; 

            $this->view('users/v_login', $data);
        }
    }
?>