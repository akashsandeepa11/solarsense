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
                    redirect('homeowner/dashboard');
                    exit; // Force exit after redirect
                    break;
                case ROLE_INSTALLER_ADMIN:
                    error_log("DEBUG: Redirecting installer admin");
                    redirect('installeradmin/dashboard');
                    exit;
                    break;
                case ROLE_OPERATION_MANAGER:
                    error_log("DEBUG: Redirecting operation manager");
                    redirect('operationmanager/dashboard');
                    exit;
                    break;
                case ROLE_INVENTORY_MANAGER:
                    error_log("DEBUG: Redirecting inventory manager");
                    redirect('inventorymanager/inventory');
                    exit;
                    break;
                case ROLE_SERVICE_AGENT:
                    error_log("DEBUG: Redirecting service agent");
                    redirect('serviceagent/tasks');
                    exit;
                    break;
                case ROLE_SUPER_ADMIN:
                    error_log("DEBUG: Redirecting super admin");
                    redirect('superadmin/dashboard');
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
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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

                        setToast('Registration successful! We’ll contact you soon.', 'success');
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

        public function installer_registration_success() {
            $this->view('pages/auth/installer_registration_success', layout: "main");
        }

        public function add_installer_verification(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                // Initial load
                $this->view('pages/auth/installer_registration', [
                    'user' => $this->userModel
                ], layout: 'main');
                return;
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Collect form data (MATCH FORM NAMES)
            $data = [
                'company_name' => trim($_POST['company_name'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'number_of_employees' => trim($_POST['number_of_employees'] ?? ''),
                'website' => trim($_POST['website'] ?? ''),
                'district' => trim($_POST['district'] ?? ''),
                'postal_code' => trim($_POST['postal_code'] ?? ''),
                'contact_number' => trim($_POST['contact_number'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'service_type' => trim($_POST['service_type'] ?? ''),
                'years_of_experience' => trim($_POST['years_of_experience'] ?? ''),
                'completed_projects' => trim($_POST['completed_projects'] ?? ''),
                'service_areas' => trim($_POST['service_areas'] ?? ''),

                // Error fields
                'company_name_err' => '',
                'email_err' => '',
                'contact_number_err' => '',
                'address_err' => '',
                'district_err' => '',
                'service_type_err' => ''
            ];

            // Validation
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Company name is required';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Valid email is required';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already registered';
            }

            if (empty($data['contact_number'])) {
                $data['contact_number_err'] = 'Contact number is required';
            }

            if (empty($data['address'])) {
                $data['address_err'] = 'Address is required';
            }

            if (empty($data['district'])) {
                $data['district_err'] = 'District is required';
            }

            if (empty($data['service_type'])) {
                $data['service_type_err'] = 'Service type is required';
            }

            // Check for errors
            foreach ($data as $key => $value) {
                if (str_ends_with($key, '_err') && !empty($value)) {
                    $this->view('pages/auth/installer_registration', $data, layout: 'main');
                    return;
                }
            }

            // Save to DB (PASS ALL REQUIRED FIELDS)
            if ($this->userModel->add_installer_verification($data)) {
                setToast('Request submitted successfully', 'success');
                redirect('auth/installerRegistrationHandler');
                return;
            }

            setToast('Something went wrong during registration. ', 'error');
            $this->view('pages/auth/installer_registration', $data, layout: 'main');
        }

        // ── Forgot Password ───────────────────────────────────────────────

        /**
         * Step 1: Show the "enter your email" form, then send the secret link.
         * Route: GET/POST auth/forgot-password
         */
        public function forgot_password() {
            $this->blockIfAuthenticated();

            $data = [
                'email'     => '',
                'email_err' => '',
                'sent'      => false,
            ];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = trim($_POST['email'] ?? '');

                if (empty($email)) {
                    $data['email_err'] = 'Please enter your email address.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email address.';
                } else {
                    $user = $this->userModel->getUserByEmail($email);

                    // Always show "sent" to avoid user enumeration attacks
                    if ($user) {
                        // Generate a cryptographically secure token
                        $token     = bin2hex(random_bytes(32)); // 64-char hex string
                        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

                        $this->userModel->savePasswordResetToken((int) $user->user_id, $token, $expiresAt);

                        $resetUrl = URLROOT . '/auth/reset-password?token=' . $token;
                        sendPasswordResetEmail($email, $user->full_name, $resetUrl);
                    }

                    $data['email'] = $email;
                    $data['sent']  = true;
                }
            }

            $this->view('pages/auth/forgot_password', $data);
        }

        /**
         * Step 2: Validate the token and let the user set a new password.
         * Route: GET/POST auth/reset-password
         */
        public function reset_password() {
            $this->blockIfAuthenticated();

            $token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));

            $data = [
                'token'         => $token,
                'password_err'  => '',
                'confirm_err'   => '',
                'invalid_token' => false,
            ];

            // Validate token before doing anything
            if (empty($token)) {
                $data['invalid_token'] = true;
                $this->view('pages/auth/reset_password', $data);
                return;
            }

            $record = $this->userModel->findValidResetToken($token);

            if (!$record) {
                $data['invalid_token'] = true;
                $this->view('pages/auth/reset_password', $data);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $password        = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 8) {
                    $data['password_err'] = 'Password must be at least 8 characters.';
                } elseif (!preg_match('/[A-Z]/', $password)) {
                    $data['password_err'] = 'Password must contain at least one uppercase letter.';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $data['password_err'] = 'Password must contain at least one number.';
                }

                if (empty($data['password_err']) && $password !== $confirmPassword) {
                    $data['confirm_err'] = 'Passwords do not match.';
                }

                if (empty($data['password_err']) && empty($data['confirm_err'])) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $this->userModel->resetPassword((int) $record->user_id, $hashedPassword);

                    setToast('Password reset successfully! You can now log in.', 'success');
                    redirect('auth/login');
                    return;
                }
            }

            $this->view('pages/auth/reset_password', $data);
        }

        
    }

?>