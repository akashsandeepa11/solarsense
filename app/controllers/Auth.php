<?php

    class Auth extends Controller{

        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('M_Auth');
        }

        public function createUserSession($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_type'] = $user->type;

            //create switch case for redirecting based on user type
            switch($user->type) {
                case ROLE_HOMEOWNER:
                    redirect('homeowner/dashboard');
                    break;
                case ROLE_INSTALLER_ADMIN:
                    redirect('installeradmin/dashboard');
                    break;
                case ROLE_OPERATION_MANAGER:
                    redirect('operationmanager/dashboard');
                    break;
                case ROLE_INVENTORY_MANAGER:
                    redirect('inventorymanager/inventory');
                    break;
                case ROLE_SERVICE_AGENT:
                    redirect('serviceagent/tasks');
                    break;
                case ROLE_SUPER_ADMIN:
                    redirect('superadmin/dashboard');
                    break;
                default:
                    redirect('auth/login');
                    break;
            }
        }

        public function logout() {
            // Store the toast message before destroying session
            $toastMessage = [
                'message' => 'You have been logged out successfully.',
                'type' => 'success'
            ];
            
            // Unset only specific user session variables, not the entire session
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_type']);

            // Set the toast after unsetting user data
            $_SESSION['toast'] = $toastMessage;
            
            redirect('auth/login');
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
            $data = [];
            $this->view('pages/auth/installer_registration', $data, layout: "main");
        }

        public function installerRegistrationHandler(){
            
            $data = [];
            $this->view('pages/auth/installerRegistrationHandler', $data, layout: "main");
        }

        
    }

?>