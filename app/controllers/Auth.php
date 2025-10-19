<?php

    class Auth extends Controller{

        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('M_Auth');
        }

        public function createUserSession($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;

            redirect('homeowner/dashboard');
        }

        public function logout() {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            
            session_destroy();
        
            redirect('/auth/login');
        }
        
        public function login(){
            if($_SERVER["REQUEST_METHOD"]=='POST'){

                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),

                    'email_err' => '',
                    'password_err' => '',
                ];

                if(empty($data['email'])){
                    $data["email_err"] = 'Please enter a email';
                }else{
                    if($this->userModel->findUserByEmail($data['email'])){
                        // User is found
                    }else{
                        $data['email_err'] = "User not found";
                    }
                }

                // Validate the Password
                if(empty($data['password'])){
                    $data['password_err'] = "Please enter the password";
                }

                // if no error found login the user
                if(empty($data['email_err']) && empty($data['password_err'])){
                    // log  the user
                    $loggedUser = $this->userModel->login($data['email'], $data['password']);

                    if($loggedUser){
                        // User Authenticated
                        // Create user sessions
                        setToast('Login successful! Welcome back.', 'success');
                        $this->createUserSession($loggedUser);
                    }else{
                        $data['password_err'] = "Password Incorrect";
                        setToast('Incorrect password. Please try again.', 'error');
                        $this->view('pages/auth/login', $data);
                    }
                }else{
                    // Load view with errors
                    $this->view('pages/auth/login', $data);
                }

            }else{

                $data = [
                    'email' => '',
                    'password' => '',

                    'email_err' => '',
                    'password_err' => '',
                ];

                $this->view('pages/auth/login', $data);
            }

        }

        public function installer_registration(){
            $data = ['name'=>'akash'];
            $this->view('pages/auth/register_installer', $data, layout: "main");
        }
    }

?>