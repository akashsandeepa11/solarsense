<?php

    class Auth extends Controller{

        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('M_Auth');
        }

        public function createUserSession($user) {
            $_SESSION['user_id'] = $user->user_id; // Changed from $user->id to $user->user_id
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_type'] = $user->type;
            $_SESSION['user_name'] = $user->full_name;

            // DEBUG
            error_log("DEBUG: User type is: " . $user->type);
            error_log("DEBUG: About to redirect...");

            //create switch case for redirecting based on user type
            switch($user->type) {
                case ROLE_HOMEOWNER:
                    error_log("DEBUG: Redirecting homeowner to HomeOwner/dashboard");
                    redirect('HomeOwner/dashboard');
                    exit; // Force exit after redirect
                    break;
                case ROLE_INSTALLER_ADMIN:
                    error_log("DEBUG: Redirecting installer admin");
                    redirect('InstallerAdmin/dashboard');
                    exit;
                    break;
                case ROLE_OPERATION_MANAGER:
                    error_log("DEBUG: Redirecting operation manager");
                    redirect('OperationManager/dashboard');
                    exit;
                    break;
                case ROLE_INVENTORY_MANAGER:
                    error_log("DEBUG: Redirecting inventory manager");
                    redirect('InventoryManager/inventory');
                    exit;
                    break;
                case ROLE_SERVICE_AGENT:
                    error_log("DEBUG: Redirecting service agent");
                    redirect('ServiceAgent/tasks');
                    exit;
                    break;
                case ROLE_SUPER_ADMIN:
                    error_log("DEBUG: Redirecting super admin");
                    redirect('SuperAdmin/dashboard');
                    exit;
                    break;
                default:
                    error_log("DEBUG: User type not recognized, redirecting to login");
                    redirect('auth/login');
                    exit;
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
            unset($_SESSION['user_name']);

            // Set the toast after unsetting user data
            $_SESSION['toast'] = $toastMessage;
            
            redirect('auth/login');
        }
        
        public function login(){
            // Block authenticated users from accessing login page
            $this->blockIfAuthenticated();
            
            if($_SERVER["REQUEST_METHOD"]=='POST'){

                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
            // Block authenticated users from accessing registration page
            $this->blockIfAuthenticated();
            
            $data = [];
            $this->view('pages/auth/installer_registration', $data, layout: "main");
        }

        public function installerRegistrationHandler() {
            // Block authenticated users from accessing registration page
            $this->blockIfAuthenticated();
            
            // Check if form is submitted (POST request)
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Sanitize and collect form inputs
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'companyName'      => trim($_POST['companyName'] ?? ''),
            'physicalAddress'  => trim($_POST['physicalAddress'] ?? ''),
            'contactNumber'    => trim($_POST['contactNumber'] ?? ''),
            'email'            => trim($_POST['email'] ?? ''),
            'companyName_err'  => '',
            'physicalAddress_err' => '',
            'contactNumber_err' => '',
            'email_err' => '',
            'success' => false
        ];

        // Validate inputs
        if (empty($data['companyName'])) {
            $data['companyName_err'] = 'Please enter your full name.';
        }

        if (empty($data['physicalAddress'])) {
            $data['physicalAddress_err'] = 'Please enter your address.';
        }

        if (empty($data['contactNumber'])) {
            $data['contactNumber_err'] = 'Please enter a contact number.';
        } elseif (!preg_match("/^[0-9]{10,15}$/", $data['contactNumber'])) {
            $data['contactNumber_err'] = 'Please enter a valid phone number.';
        }

        if (empty($data['email'])) {
            $data['email_err'] = 'Please enter an email.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Invalid email format.';
        }

        //If no validation errors, insert into DB
        if (
            empty($data['companyName_err']) &&
            empty($data['physicalAddress_err']) &&
            empty($data['contactNumber_err']) &&
            empty($data['email_err'])
        ) {
            $installerModel = $this->model('InstallerModel');

            if ($installerModel->add_company($data)) {
                $data['success'] = true;

                // Redirect or load success view
                flash('register_success', 'Registration successful! We’ll contact you soon.');
                redirect('auth/login'); // or wherever you want
                return;
            } else {
                $data['general_err'] = 'Something went wrong. Please try again later.';
            }
        }

        // If validation fails or insertion fails, reload the form with errors
        $this->view('pages/auth/installerRegistrationHandler', $data, layout: "main");

    } else {
        // GET request — load the form
        $data = [
            'companyName' => '',
            'physicalAddress' => '',
            'contactNumber' => '',
            'email' => '',
            'companyName_err' => '',
            'physicalAddress_err' => '',
            'contactNumber_err' => '',
            'email_err' => ''
        ];

        $this->view('pages/auth/installerRegistrationHandler', $data, layout: "main");
    }
}

        
    }

?>