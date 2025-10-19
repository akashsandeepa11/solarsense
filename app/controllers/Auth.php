<?php

    class Auth extends Controller{
        public function __construct(){

        }
        
        public function login(){
            if($_SERVER['REQUEST_METHOD']== 'POST'){
                //form is submmitting 
                $_POST= filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data =[
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),

                    'email_err' => '',
                    'password_err' => ''
                ];

                //validate email
                if(empty($data['email'])){
                    $data['email_err']= 'Please enter email';
                }
                else{
                    if($this->userModel->finderUserByEmail($data['email']) >0){
                        //user is found
                    }
                    else{
                        //user is not found
                        $data['email_err']= 'User is not found';
                    }
                }

                //validate password
                if(empty($data['password'])){
                    $data['password_err']= 'Please enter password';
                }
                
                //if no error is found, login user
                if(empty($data['email_err'])&&empty($data['password_err'])){

                }
            }
            else{
                //initial form
                $data =[
                    'email' => '',
                    'password' => '',

                    'email_err' => '',
                    'password_err' => ''
                ];

                //load view
                $this->view('pages/auth/login', $data);
            }
        }

        public function add_customer(){
            $data = ['name'=>'akash'];
            $this->view('pages/auth/add_customer', $data, layout: "main");
        }

        public function installer_registration(){
            $data = ['name'=>'akash'];
            $this->view('pages/auth/register_installer', $data, layout: "main");
        }
    }

?>