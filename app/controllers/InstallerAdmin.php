<?php

    class InstallerAdmin extends Controller{

        private $fleetModel;

        private $user = [
            'role' => ROLE_INSTALLER_ADMIN,
            
        ];
        
        public function __construct(){
            $this->fleetModel = $this->model('M_Fleet');
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // --- Admin-Specific Pages ---

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/dashboard', $data, layout: 'dashboard');
        }

        public function fleet($page = 'dashboard'){
            $data = [
                'user' => $this->user,
            ];
            
            if($page === 'add_customer'){

                return $this->add_customer();
            }
            
            $this->view('pages/installer_admin/fleet_dashboard', $data, layout: 'dashboard');
        }
        
        public function add_customer(){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Form is submitting
                // Validate the data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Get current step
                $currentStep = isset($_POST['current_step']) ? (int)$_POST['current_step'] : 1;

                // Input data from form
                $data = [
                    'fullName' => trim($_POST['fullName'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'contactNumber' => trim($_POST['contactNumber'] ?? ''),
                    'physicalAddress' => trim($_POST['physicalAddress'] ?? ''),
                    'nic' => trim($_POST['nic'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
                    'confirmPassword' => trim($_POST['confirmPassword'] ?? ''),
                    'district' => trim($_POST['district'] ?? ''),
                    'systemCapacity' => trim($_POST['systemCapacity'] ?? ''),
                    'panelTilt' => trim($_POST['panelTilt'] ?? ''),
                    'panelAzimuth' => trim($_POST['panelAzimuth'] ?? ''),
                    'installationDate' => trim($_POST['installationDate'] ?? ''),
                    'panelBrand' => trim($_POST['panelBrand'] ?? ''),
                    'inverterBrand' => trim($_POST['inverterBrand'] ?? ''),
                    'cebAccount' => trim($_POST['cebAccount'] ?? ''),
                    'current_step' => $currentStep,

                    // Error fields
                    'fullName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'physicalAddress_err' => '',
                    'nic_err' => '',
                    'password_err' => '',
                    'confirmPassword_err' => '',
                    'district_err' => '',
                    'systemCapacity_err' => '',
                    'panelTilt_err' => '',
                    'panelAzimuth_err' => '',
                    'installationDate_err' => '',
                    'cebAccount_err' => ''
                ];

                // Validate Step 1: Personal & Contact Details
                if($currentStep == 1) {
                    if(empty($data['fullName'])){
                        $data['fullName_err'] = "Please enter full name";
                    }

                    if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                        $data['email_err'] = "Please enter a valid email address";
                    }

                    if(empty($data['contactNumber']) || !preg_match('/^[0-9\-\+\s\(\)]+$/', $data['contactNumber'])){
                        $data['contactNumber_err'] = "Please enter a valid contact number";
                    }

                    if(empty($data['physicalAddress'])){
                        $data['physicalAddress_err'] = "Please enter physical address";
                    }

                    if(empty($data['nic'])){
                        $data['nic_err'] = "Please enter NIC/ID number";
                    }

                    if(empty($data['password']) || strlen($data['password']) < 6){
                        $data['password_err'] = "Password must be at least 6 characters";
                    }

                    if(empty($data['confirmPassword'])){
                        $data['confirmPassword_err'] = "Please confirm password";
                    } elseif($data['password'] !== $data['confirmPassword']){
                        $data['confirmPassword_err'] = "Passwords do not match";
                    }

                    if(empty($data['district'])){
                        $data['district_err'] = "Please select a district";
                    }

                    // Check for errors in step 1
                    $step1Errors = !empty($data['fullName_err']) || !empty($data['email_err']) || 
                                   !empty($data['contactNumber_err']) || !empty($data['physicalAddress_err']) ||
                                   !empty($data['nic_err']) || !empty($data['password_err']) ||
                                   !empty($data['confirmPassword_err']) || !empty($data['district_err']);

                    if($step1Errors){
                        // Reload form with errors on step 1
                        $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                        return;
                    }
                }

                // Validate Step 2: Solar System Specifications
                if($currentStep == 2) {
                    if(empty($data['systemCapacity']) || !is_numeric($data['systemCapacity'])){
                        $data['systemCapacity_err'] = "Please enter valid system capacity";
                    }

                    if(empty($data['panelTilt']) || !is_numeric($data['panelTilt'])){
                        $data['panelTilt_err'] = "Please enter valid panel tilt";
                    }

                    if(empty($data['panelAzimuth'])){
                        $data['panelAzimuth_err'] = "Please select panel azimuth";
                    }

                    if(empty($data['installationDate'])){
                        $data['installationDate_err'] = "Please select installation date";
                    }

                    // Check for errors in step 2
                    $step2Errors = !empty($data['systemCapacity_err']) || !empty($data['panelTilt_err']) || 
                                   !empty($data['panelAzimuth_err']) || !empty($data['installationDate_err']);

                    if($step2Errors){
                        // Reload form with errors on step 2
                        $data['current_step'] = 2;
                        $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                        return;
                    }
                }

                // Validate Step 3: Utility Account Details
                if($currentStep == 3) {
                    if(empty($data['cebAccount'])){
                        $data['cebAccount_err'] = "Please enter CEB account number";
                    }

                    if($data['cebAccount_err']){
                        // Reload form with errors on step 3
                        $data['current_step'] = 3;
                        $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                        return;
                    }
                }

                // If step 4 (submission) - save to database
                if($currentStep == 4) {
                    // Prepare data for model
                    $userData = [
                        'email' => $data['email'],
                        'password' => password_hash($data['password'], PASSWORD_DEFAULT)
                    ];

                    $customerData = [
                        'full_name' => $data['fullName'],
                        'address' => $data['physicalAddress'],
                        'contact' => $data['contactNumber'],
                        'nic' => $data['nic']
                    ];

                    $panelData = [
                        'capacity' => $data['systemCapacity'],
                        'tilt' => $data['panelTilt'],
                        'azimuth' => $data['panelAzimuth'],
                        'panel_brand' => $data['panelBrand'],
                        'inverter_brand' => $data['inverterBrand'],
                        'installation_date' => $data['installationDate']
                    ];

                    // Call model to save data
                    if($this->fleetModel->add_customer($userData, $customerData, $panelData)) {
                        setToast('Customer Added Successfully', 'success');
                        redirect('installeradmin/fleet/add_customer');
                    } else {
                        $data['fullName_err'] = "Error saving customer. Please try again.";
                        $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                    }
                    return;
                }

            } else {
                // Initial form load
                $data = [
                    'fullName' => '',
                    'email' => '',
                    'contactNumber' => '',
                    'physicalAddress' => '',
                    'nic' => '',
                    'password' => '',
                    'confirmPassword' => '',
                    'district' => '',
                    'systemCapacity' => '',
                    'panelTilt' => '',
                    'panelAzimuth' => '',
                    'installationDate' => '',
                    'panelBrand' => '',
                    'inverterBrand' => '',
                    'cebAccount' => '',
                    'current_step' => 1,

                    'fullName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'physicalAddress_err' => '',
                    'nic_err' => '',
                    'password_err' => '',
                    'confirmPassword_err' => '',
                    'district_err' => '',
                    'systemCapacity_err' => '',
                    'panelTilt_err' => '',
                    'panelAzimuth_err' => '',
                    'installationDate_err' => '',
                    'cebAccount_err' => ''
                ];

                $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
            }
        }

        public function team(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/team', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/profile', $data, layout: 'dashboard');
        }
        
        
    }

?>
