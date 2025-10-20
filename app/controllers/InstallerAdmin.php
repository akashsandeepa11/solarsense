<?php

    class InstallerAdmin extends Controller{

        private $fleetModel;
        private $authModel;
        private $teamModel;

        private $user = [
            'role' => ROLE_INSTALLER_ADMIN,
            
        ];
        
        public function __construct(){
            $this->fleetModel = $this->model('M_Fleet');
            $this->authModel = $this->model('M_Auth');
            $this->teamModel = $this->model('M_Team');
            // Here you would add logic to ensure only an admin can access these methods.
        }

        // -----------------Admin Dashboard Methods -----------------

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/dashboard', $data, layout: 'dashboard');
        }

        // --- Fleet Management ---
        public function fleet($page = 'dashboard', $customerId = null){
            
            if($page === 'add_customer'){               
                return $this->add_customer();
            }

            if($page === 'customer_details'){
                return $this->customerdetails($customerId);
            }

            if($page === 'view'){
                return $this->view_customer();
            }

            if($page === 'edit_customer'){
                return $this->edit_customer();
            }

            if($page === 'delete_customer'){
                return $this->delete_customer($customerId);
            }

            $data = [
                'user' => $this->user,
                'customers' => $this->fleetModel->get_customer_by_company(1)
            ];


            
            $this->view('pages/installer_admin/fleet_dashboard', $data, layout: 'dashboard');
        }

        public function customerdetails($customerId = null){
            $data = [
                'user' => $this->user,
                'customerId' => $customerId
            ];
            
            $this->view('pages/installer_admin/customer_details', $data, layout: 'dashboard');
        }
        
        public function add_customer(): void{
            
            
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
                }

                // Validate all fields
                if(empty($data['fullName'])){
                    $data['fullName_err'] = "Please enter full name";
                }

                if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                    $data['email_err'] = "Please enter a valid email address";
                }else{
                    // Check email is already registed or not
                    if($this->authModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already registered';
                    }
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
                    redirect('installeradmin/fleet');
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

        public function view_customer(): void{
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer_admin/view_customer', $data, layout: 'dashboard');
        }

        public function edit_customer(): void{
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer_admin/edit_customer', $data, layout: 'dashboard');
        }

        // public function delete_customer(): void{
        //     $data = [
        //         'user' => $this->user,
        //     ];

        //     $this->view('pages/installer_admin/delete_customer', $data, layout: 'dashboard');
        // }

        public function delete_customer($customerId = null){
            // Only process POST requests for deletion
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                redirect('installeradmin/fleet');
                return;
            }

            if(empty($customerId)){
                setToast('Invalid customer ID', 'error');
                redirect('installeradmin/fleet');
                return;
            }

            // Call model to delete customer
            if($this->fleetModel->delete_customer($customerId)){
                redirect('installeradmin/fleet');
                setToast('Customer Deleted Successfully', 'success');
            } else {
                setToast('Failed to delete customer. Please try again.', 'error');
            }

            redirect('installeradmin/fleet');
        }


        // --- Team Management ---
        public function team($page = 'dashboard', $agentId = null){
            
            if($page === 'add_service_agent'){
                return $this->add_service_agent();
            }
            
            if($page === 'agent_details'){
                return $this->agent_details($agentId);
            }
            
            if($page === 'edit_agent'){
                return $this->edit_agent($agentId);
            }
            
            if($page === 'delete_agent'){
                return $this->delete_agent($agentId);
            }
            
            $data = [
                'user' => $this->user,
                'agents' => $this->teamModel->get_service_agents_by_company(1)
            ];
           
            $this->view('pages/installer_admin/team', $data, layout: 'dashboard');
        }

        public function add_service_agent(){
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Form is submitting
                // Validate the data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Input data from form
                $data = [
                    'user' => $this->user,
                    'mode' => $_POST['mode'] ?? 'add',
                    'agentId' => $_POST['agentId'] ?? '',
                    'fullName' => trim($_POST['fullName'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'contactNumber' => trim($_POST['contactNumber'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'nic' => trim($_POST['nic'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
                    'confirmPassword' => trim($_POST['confirmPassword'] ?? ''),
                    'district' => trim($_POST['district'] ?? ''),
                    'specialization' => trim($_POST['specialization'] ?? ''),
                    'experienceYears' => trim($_POST['experienceYears'] ?? ''),
                    'availability' => trim($_POST['availability'] ?? ''),
                    'certifications' => trim($_POST['certifications'] ?? ''),

                    // Error fields
                    'fullName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'address_err' => '',
                    'nic_err' => '',
                    'password_err' => '',
                    'confirmPassword_err' => '',
                    'district_err' => '',
                    'specialization_err' => '',
                    'experienceYears_err' => '',
                    'availability_err' => '',
                    'certifications_err' => ''
                ];

                // Validation Logic
                // Validate Full Name
                if(empty($data['fullName'])){
                    $data['fullName_err'] = 'Full Name is required';
                }

                // Validate Email
                if(empty($data['email'])){
                    $data['email_err'] = 'Email is required';
                } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                    $data['email_err'] = 'Please enter a valid email';
                }

                // Validate Contact Number
                if(empty($data['contactNumber'])){
                    $data['contactNumber_err'] = 'Contact Number is required';
                } elseif(!preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $data['contactNumber'])){
                    $data['contactNumber_err'] = 'Please enter a valid phone number';
                }

                // Validate NIC
                if(empty($data['nic'])){
                    $data['nic_err'] = 'NIC/ID Number is required';
                }

                // Validate Address
                if(empty($data['address'])){
                    $data['address_err'] = 'Address is required';
                }

                // Validate District
                if(empty($data['district'])){
                    $data['district_err'] = 'District is required';
                }

                // Validate Password (required only in add mode)
                if($data['mode'] === 'add'){
                    if(empty($data['password'])){
                        $data['password_err'] = 'Password is required';
                    } elseif(strlen($data['password']) < 6){
                        $data['password_err'] = 'Password must be at least 6 characters';
                    }

                    // Validate Confirm Password (required only in add mode)
                    if(empty($data['confirmPassword'])){
                        $data['confirmPassword_err'] = 'Confirm Password is required';
                    } elseif($data['password'] !== $data['confirmPassword']){
                        $data['confirmPassword_err'] = 'Passwords do not match';
                    }
                } else {
                    // In edit mode, password is optional
                    if(!empty($data['password'])){
                        if(strlen($data['password']) < 6){
                            $data['password_err'] = 'Password must be at least 6 characters';
                        }
                        if($data['password'] !== $data['confirmPassword']){
                            $data['confirmPassword_err'] = 'Passwords do not match';
                        }
                    }
                }

                // Validate Specialization
                if(empty($data['specialization'])){
                    $data['specialization_err'] = 'Specialization is required';
                }

                // Validate Experience Years
                if(empty($data['experienceYears'])){
                    $data['experienceYears_err'] = 'Years of Experience is required';
                } elseif(!is_numeric($data['experienceYears']) || $data['experienceYears'] < 0){
                    $data['experienceYears_err'] = 'Please enter a valid number';
                }

                // Validate Availability
                if(empty($data['availability'])){
                    $data['availability_err'] = 'Availability is required';
                }

                // Check for validation errors
                if(empty($data['fullName_err']) && empty($data['email_err']) && empty($data['contactNumber_err']) && 
                   empty($data['nic_err']) && empty($data['address_err']) && empty($data['district_err']) && 
                   empty($data['password_err']) && empty($data['confirmPassword_err']) && 
                   empty($data['specialization_err']) && empty($data['experienceYears_err']) && empty($data['availability_err'])){

                    // All validations passed - prepare data for model
                    $agentData = [
                        'full_name' => $data['fullName'],
                        'email' => $data['email'],
                        'contact_number' => $data['contactNumber'],
                        'nic' => $data['nic'],
                        'address' => $data['address'],
                        'district' => $data['district'],
                        'specialization' => $data['specialization'],
                        'experience_years' => $data['experienceYears'],
                        'availability' => $data['availability'],
                        'certifications' => $data['certifications'],
                        'status' => 'active'
                    ];

                    // Add password only if provided (new or updated password)
                    if(!empty($data['password'])){
                        $agentData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    }

                    // Add creation date only for new agents
                    if($data['mode'] === 'add'){
                        $agentData['created_date'] = date('Y-m-d H:i:s');
                    }

                    // Prepare user data for model
                    $userData = [
                        'email' => $data['email'],
                        'password' => password_hash($data['password'], PASSWORD_DEFAULT)
                    ];

                    // Call model to save data
                    if($data['mode'] === 'add'){
                        // Check if email already exists
                        if($this->authModel->findUserByEmail($data['email'])){
                            $data['email_err'] = 'Email is already registered';
                            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                            return;
                        }

                        // Check if NIC already exists
                        if($this->teamModel->nic_exists($data['nic'])){
                            $data['nic_err'] = 'NIC/ID Number is already registered';
                            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                            return;
                        }

                        if($this->teamModel->add_service_agent($userData, $agentData)){
                            setToast('Service Agent Added Successfully', 'success');
                            redirect('installeradmin/team');
                        } else {
                            setToast('Failed to add service agent. Please try again.', 'error');
                            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                        }
                    } else {
                        // Update mode
                        if($this->teamModel->update_service_agent($data['agentId'], $data['agentId'], $userData, $agentData)){
                            setToast('Service Agent Updated Successfully', 'success');
                            redirect('installeradmin/team/agent_details/' . $data['agentId']);
                        } else {
                            setToast('Failed to update service agent. Please try again.', 'error');
                            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                        }
                    }
                } else {
                    // Show form again with errors
                    $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                }
                return;

            } else {
                // Initial form load (add mode)
                $data = [
                    'user' => $this->user,
                    'mode' => 'add',
                    'agentId' => '',
                    'fullName' => '',
                    'email' => '',
                    'contactNumber' => '',
                    'address' => '',
                    'nic' => '',
                    'password' => '',
                    'confirmPassword' => '',
                    'district' => '',
                    'specialization' => '',
                    'experienceYears' => '',
                    'availability' => '',
                    'certifications' => '',

                    'fullName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'address_err' => '',
                    'nic_err' => '',
                    'password_err' => '',
                    'confirmPassword_err' => '',
                    'district_err' => '',
                    'specialization_err' => '',
                    'experienceYears_err' => '',
                    'availability_err' => '',
                    'certifications_err' => ''
                ];

                $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
            }
        }

        public function agent_details($agentId = null){
            $data = [
                'user' => $this->user,
            ];
            
            // TODO: Fetch agent details from database using $agentId
            // For now, using sample data
            
            $this->view('pages/installer_admin/agent_details', $data, layout: 'dashboard');
        }

        public function edit_agent($agentId = null){
            $data = [
                'user' => $this->user,
                'mode' => 'edit',
                'agentId' => $agentId,
                'fullName' => '',
                'email' => '',
                'contactNumber' => '',
                'address' => '',
                'nic' => '',
                'district' => '',
                'specialization' => '',
                'experienceYears' => '',
                'availability' => '',
                'certifications' => '',

                'fullName_err' => '',
                'email_err' => '',
                'contactNumber_err' => '',
                'address_err' => '',
                'nic_err' => '',
                'password_err' => '',
                'confirmPassword_err' => '',
                'district_err' => '',
                'specialization_err' => '',
                'experienceYears_err' => '',
                'availability_err' => '',
                'certifications_err' => ''
            ];
            
            // TODO: Fetch agent details from database using $agentId and populate $data
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Handle update logic similar to add_service_agent
                // TODO: Implement update validation and database save
                setToast('Service Agent Updated Successfully', 'success');
                redirect('installeradmin/team/agent_details/' . $agentId);
                return;
            }
            
            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
        }

        public function delete_agent($agentId = null){
            // Only process POST requests for deletion
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                redirect('installeradmin/team');
                return;
            }
            
            if(empty($agentId)){
                setToast('Invalid agent ID', 'error');
                redirect('installeradmin/team');
                return;
            }
            
            // Call model to delete service agent
            if($this->teamModel->delete_service_agent($agentId)){
                redirect('installeradmin/team');
                setToast('Service Agent Deleted Successfully', 'success');
            } else {
                setToast('Failed to delete service agent. Please try again.', 'error');
            }
            
            redirect('installeradmin/team');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/profile', $data, layout: 'dashboard');
        }
        
        
    }

?>
