<?php

    class InstallerAdmin extends Controller{

        private $fleetModel;
        private $authModel;

        private $user = [
            'role' => ROLE_INSTALLER_ADMIN,
            
        ];
        
        public function __construct(){
            $this->fleetModel = $this->model('M_Fleet');
            $this->authModel = $this->model('M_Auth');
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

                // Input data from form
                $data = [
                    'user' => $this->user,
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
                    'panelBrand_err' => '',
                    'inverterBrand_err' => '',
                    'cebAccount_err' => ''
                ];

                // Convert text azimuth values to numeric degrees (backwards compatibility)
                $azimuthMap = [
                    'North' => '0',
                    'North-East' => '45',
                    'East' => '90',
                    'South-East' => '135',
                    'South' => '180',
                    'South-West' => '225',
                    'West' => '270',
                    'North-West' => '315'
                ];
                if (isset($azimuthMap[$data['panelAzimuth']])) {
                    $data['panelAzimuth'] = $azimuthMap[$data['panelAzimuth']];
                }else{
                    // Check email is already registed or not
                    if($this->authModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already registered';
                    }
                }

                // Validate all fields
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

                if(empty($data['district'])){
                    $data['district_err'] = "Please select a district";
                }

                if(empty($data['password']) || strlen($data['password']) < 6){
                    $data['password_err'] = "Password must be at least 6 characters";
                }

                if(empty($data['confirmPassword'])){
                    $data['confirmPassword_err'] = "Please confirm password";
                } elseif($data['password'] !== $data['confirmPassword']){
                    $data['confirmPassword_err'] = "Passwords do not match";
                }

                if(empty($data['systemCapacity']) || !is_numeric($data['systemCapacity'])){
                    $data['systemCapacity_err'] = "Please enter valid system capacity";
                }

                if(empty($data['panelTilt']) || !is_numeric($data['panelTilt'])){
                    $data['panelTilt_err'] = "Please enter valid panel tilt";
                }

                if(empty($data['panelAzimuth']) || !is_numeric($data['panelAzimuth'])){
                    $data['panelAzimuth_err'] = "Please select valid panel azimuth";
                }

                if(empty($data['installationDate'])){
                    $data['installationDate_err'] = "Please select installation date";
                }

                if(empty($data['panelBrand'])){
                    $data['panelBrand_err'] = "Please select panel brand";
                }

                if(empty($data['inverterBrand'])){
                    $data['inverterBrand_err'] = "Please select inverter brand";
                }

                if(empty($data['cebAccount'])){
                    $data['cebAccount_err'] = "Please enter CEB account number";
                }

                // Check for any errors
                $hasErrors = !empty($data['fullName_err']) || !empty($data['email_err']) || 
                             !empty($data['contactNumber_err']) || !empty($data['physicalAddress_err']) ||
                             !empty($data['nic_err']) || !empty($data['password_err']) ||
                             !empty($data['confirmPassword_err']) || !empty($data['district_err']) ||
                             !empty($data['systemCapacity_err']) || !empty($data['panelTilt_err']) ||
                             !empty($data['panelAzimuth_err']) || !empty($data['installationDate_err']) ||
                             !empty($data['panelBrand_err']) || !empty($data['inverterBrand_err']) ||
                             !empty($data['cebAccount_err']);

                if($hasErrors){
                    // Reload form with errors
                    $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                    return;
                }

                // All validation passed - save to database
                $userData = [
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT)
                ];

                $customerData = [
                    'full_name' => $data['fullName'],
                    'address' => $data['physicalAddress'],
                    'contact' => $data['contactNumber'],
                    'nic' => $data['nic'],
                    'district' => $data['district']
                ];

                $panelData = [
                    'system_capacity' => $data['systemCapacity'],
                    'panel_tilt' => $data['panelTilt'],
                    'panel_azimuth' => $data['panelAzimuth'],
                    'panel_brand' => $data['panelBrand'],
                    'inverter_brand' => $data['inverterBrand'],
                    'installation_date' => $data['installationDate'],
                    'ceb_account' => $data['cebAccount']
                ];

                // Call model to save data
                if($this->fleetModel->add_customer($userData, $customerData, $panelData)) {
                    setToast('Customer Added Successfully', 'success');
                    redirect('installeradmin/fleet/add_customer');
                } else {
                    setToast('Something went wrong during registration.', 'error');
                    $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                }
                return;

            } else {
                // Initial form load
                $data = [
                    'user' => $this->user,
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
                    'panelBrand_err' => '',
                    'inverterBrand_err' => '',
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
