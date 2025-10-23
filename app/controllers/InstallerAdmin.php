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

        public function dashboard($page='dashboard'){
            $data = [
                'user' => $this->user,
            ];

            if($page == 'system_performance'){
                
                return $this->view('pages/common/system_performance', $data, layout: 'dashboard');
            }
            
            $this->view('pages/installer_admin/dashboard', $data, layout: 'dashboard');
        }

        // --- Notifications ---
        public function notifications(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/common/notifications', $data, layout: 'dashboard');
        }

        // --- Reports ---
        public function reports(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/reports', $data, layout: 'dashboard');
        }

        // --- Fleet Management ---
        public function fleet($page = 'dashboard', $customerId = null){
            
            if($page === 'add_customer'){               
                return $this->add_customer();
            }

            if($page === 'customer_details'){
                return $this->customerdetails($customerId);
            }

            if($page === 'edit_customer'){
                return $this->edit_customer($customerId);
            }

            if($page === 'delete_customer'){
                return $this->delete_customer($customerId);
            }

            $data = [
                'user' => $this->user,
                'customers' => $this->fleetModel->get_customer_by_company(1)
            ];

            
            $this->view('pages/common/fleet_dashboard', $data, layout: 'dashboard');
        }

        public function customerdetails($customerId = null){
            $data = [
                'user' => $this->user,
                'customerId' => $customerId
            ];
            
            $this->view('pages/common/customer_details', $data, layout: 'dashboard');
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
                    'district' => $data['district'],
                    'ceb_account' => $data['cebAccount']
                ];

                $panelData = [
                    'system_capacity' => $data['systemCapacity'],
                    'panel_tilt' => $data['panelTilt'],
                    'panel_azimuth' => $data['panelAzimuth'],
                    'panel_brand' => $data['panelBrand'],
                    'inverter_brand' => $data['inverterBrand'],
                    'installation_date' => $data['installationDate'],
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

        public function edit_customer($customerId = null){
            $data = [
                'user' => $this->user,
                'mode' => 'edit',
                'customerId' => $customerId,
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

            // Fetch customer details from database
            if(!empty($customerId)){
                $customerData = $this->fleetModel->get_customer_details($customerId);

                if($customerData){
                    // Populate form with customer data
                    $data['fullName'] = $customerData->full_name ?? '';
                    $data['email'] = $customerData->email ?? '';
                    $data['contactNumber'] = $customerData->contact ?? '';
                    $data['physicalAddress'] = $customerData->address ?? '';
                    $data['nic'] = $customerData->nic ?? '';
                    $data['district'] = $customerData->district ?? '';
                    $data['systemCapacity'] = $customerData->system_capacity ?? '';
                    $data['panelTilt'] = $customerData->panel_tilt ?? '';
                    $data['panelAzimuth'] = $customerData->panel_azimuth ?? '';
                    $data['installationDate'] = $customerData->installation_date ?? '';
                    $data['panelBrand'] = $customerData->panel_brand ?? '';
                    $data['inverterBrand'] = $customerData->inverter_brand ?? '';
                    $data['cebAccount'] = $customerData->ceb_account ?? '';
                } else {
                    setToast('Customer not found', 'error');
                    redirect('installeradmin/fleet');
                    return;
                }
            } else {
                setToast('Invalid customer ID', 'error');
                redirect('installeradmin/fleet');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Form is submitting - validation and update logic
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Input data from form
                $data = [
                    'user' => $this->user,
                    'mode' => $_POST['mode'] ?? 'edit',
                    'customerId' => $_POST['customerId'] ?? '',
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

                // Password is optional in edit mode, but must match if provided
                if(!empty($data['password'])){
                    if(strlen($data['password']) < 6){
                        $data['password_err'] = "Password must be at least 6 characters";
                    }
                    if($data['password'] !== $data['confirmPassword']){
                        $data['confirmPassword_err'] = "Passwords do not match";
                    }
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



                // All validation passed - prepare data for update
                $userData = [
                    'email' => $data['email'],
                    'full_name' => $data['fullName'],
                ];

                // Add password only if provided
                if(!empty($data['password'])){
                    $userData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                $customerData = [
                    'address' => $data['physicalAddress'],
                    'contact' => $data['contactNumber'],
                    'nic' => $data['nic'],
                    'district' => $data['district'],
                    'ceb_account' => $data['cebAccount']
                ];

                $panelData = [
                    'system_capacity' => $data['systemCapacity'],
                    'panel_tilt' => $data['panelTilt'],
                    'panel_azimuth' => $data['panelAzimuth'],
                    'panel_brand' => $data['panelBrand'],
                    'inverter_brand' => $data['inverterBrand'],
                    'installation_date' => $data['installationDate'],
                ];

                // Call model to update data
                if($this->fleetModel->update_customer($data['customerId'], $userData, $customerData, $panelData)){
                    setToast('Customer Updated Successfully', 'success');
                    redirect('installeradmin/fleet');
                } else {
                    setToast('Failed to update customer. Please try again.', 'error');
                    $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                }
                return;
            }
            
            $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
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
           
            $this->view('pages/common/team', $data, layout: 'dashboard');
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
            
            $this->view('pages/common/agent_details', $data, layout: 'dashboard');
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

            // Fetch agent details from database using get_service_agent_complete() for all 18 fields
            if(!empty($agentId)){
                // Use the complete retrieval function for comprehensive data access
                $agentData = $this->teamModel->get_service_agent_complete($agentId);
                
                if($agentData){
                    // Populate form with agent data from all 18 fields
                    // Supports both snake_case and camelCase field access
                    $data['fullName'] = $agentData->full_name ?? $agentData->user_full_name ?? '';
                    $data['email'] = $agentData->email ?? '';
                    $data['contactNumber'] = $agentData->contact ?? $agentData->contact_number ?? '';
                    $data['address'] = $agentData->address ?? '';
                    $data['nic'] = $agentData->nic ?? '';
                    $data['district'] = $agentData->district ?? '';
                    $data['specialization'] = $agentData->specialization ?? '';
                    $data['experienceYears'] = $agentData->experience_years ?? $agentData->experienceYears ?? '';
                    $data['availability'] = $agentData->availability ?? '';
                    $data['certifications'] = $agentData->certifications ?? '';
                    
                    // Additional metadata available from get_service_agent_complete()
                    $data['user_type'] = $agentData->user_type ?? ROLE_SERVICE_AGENT;
                    $data['status'] = $agentData->status ?? 'active';
                    $data['register_date'] = $agentData->register_date ?? '';
                    $data['company_id'] = $agentData->company_id ?? 1;
                } else {
                    setToast('Agent not found', 'error');
                    redirect('installeradmin/team');
                    return;
                }
            } else {
                setToast('Invalid agent ID', 'error');
                redirect(url: 'installeradmin/team');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Form is submitting - validation and update logic
                // Validate the data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Input data from form
                $data = [
                    'user' => $this->user,
                    'mode' => $_POST['mode'] ?? 'edit',
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
                }elseif(!preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $data['contactNumber'])){
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

                // Validate Password (optional in edit mode, but must match if provided)
                if(!empty($data['password'])){
                    if(strlen($data['password']) < 6){
                        $data['password_err'] = 'Password must be at least 6 characters';
                    }
                    if($data['password'] !== $data['confirmPassword']){
                        $data['confirmPassword_err'] = 'Passwords do not match';
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
                    
                    // Prepare user data for model
                    $userData = [
                        'full_name' => $data['fullName'],
                        'email' => $data['email'],
                        'password' => !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : ''
                    ];

                    // Call model to update data
                    if($this->teamModel->update_service_agent($data['agentId'], $userData, $agentData)){
                        setToast('Service Agent Updated Successfully', 'success');
                        redirect('installeradmin/team');
                    } else {
                        setToast('Failed to update service agent. Please try again.', 'error');
                        $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                    }
                } else {
                    // Show form again with errors
                    $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                }
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


        public function managers($tab = 'operation_managers', $id = null, $action = null){

            // Handle add action
            if($id === 'add'){
                return $this->add_manager($tab);
            }

            // Handle edit action
            if($action === 'edit' && $id){
                return $this->edit_manager($tab, $id);
            }

            // Handle delete action
            if($action === 'delete' && $id){
                return $this->delete_manager($tab, $id);
            }

            // Handle detail view
            if($id){
                if($tab === 'operation_managers'){
                    return $this->operation_managers_detail($id);
                } elseif($tab === 'inventory_managers'){
                    return $this->inventory_managers_detail($id);
                }
            }

            // Handle list views
            if($tab === 'operation_managers'){
               return $this->operation_managers();
            } elseif($tab === 'inventory_managers'){
                return $this->inventory_managers();
            }
            
            
            redirect('installeradmin/managers/operation_managers');
        }

        // --- Manager Management ---
        public function operation_managers(){
            // Sample data for Operation Managers
            $sample_operation_managers = [
                [
                    'id' => 1,
                    'name' => 'John Smith',
                    'email' => 'john.smith@solarsense.com',
                    'specialization' => 'Installation',
                    'district' => 'Colombo',
                    'status' => 'active',
                    'pending_tasks' => 3,
                    'performance' => 95
                ],
                [
                    'id' => 2,
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.j@solarsense.com',
                    'specialization' => 'Maintenance',
                    'district' => 'Kandy',
                    'status' => 'active',
                    'pending_tasks' => 2,
                    'performance' => 88
                ],
                [
                    'id' => 3,
                    'name' => 'Michael Brown',
                    'email' => 'michael.b@solarsense.com',
                    'specialization' => 'Repair',
                    'district' => 'Galle',
                    'status' => 'on leave',
                    'pending_tasks' => 1,
                    'performance' => 92
                ],
                [
                    'id' => 4,
                    'name' => 'Lisa Chen',
                    'email' => 'lisa.chen@solarsense.com',
                    'specialization' => 'Installation',
                    'district' => 'Colombo',
                    'status' => 'active',
                    'pending_tasks' => 5,
                    'performance' => 85
                ]
            ];

            $data = [
                'user' => $this->user,
                'managerType' => 'operation_managers',
                'managers' => $sample_operation_managers
            ];
           
            $this->view('pages/installer_admin/managers_list', $data, layout: 'dashboard');
        }

        public function inventory_managers(){
            // Sample data for Inventory Managers
            $sample_inventory_managers = [
                [
                    'id' => 1,
                    'name' => 'David Wilson',
                    'email' => 'david.w@solarsense.com',
                    'warehouse' => 'Main Warehouse - Colombo',
                    'status' => 'active',
                    'inventory_items' => 245,
                    'low_stock' => 3,
                    'efficiency' => 92
                ],
                [
                    'id' => 2,
                    'name' => 'Emma Davis',
                    'email' => 'emma.d@solarsense.com',
                    'warehouse' => 'Branch Warehouse - Kandy',
                    'status' => 'active',
                    'inventory_items' => 156,
                    'low_stock' => 5,
                    'efficiency' => 88
                ],
                [
                    'id' => 3,
                    'name' => 'James Miller',
                    'email' => 'james.m@solarsense.com',
                    'warehouse' => 'Branch Warehouse - Galle',
                    'status' => 'away',
                    'inventory_items' => 98,
                    'low_stock' => 8,
                    'efficiency' => 75
                ]
            ];

            $data = [
                'user' => $this->user,
                'managerType' => 'inventory_managers',
                'managers' => $sample_inventory_managers
            ];
           
            $this->view('pages/installer_admin/managers_list', $data, layout: 'dashboard');
        }

        // Manager Detail View Methods
        public function operation_managers_detail($managerId = null){
            // Sample operation manager detail data
            $sample_manager = [
                'id' => $managerId ?? 1,
                'name' => 'John Smith',
                'email' => 'john.smith@solarsense.com',
                'contact' => '+94 77 123 4567',
                'nic' => '123456789V',
                'address' => '45 Technical Lane, Colombo 3',
                'district' => 'Colombo',
                'specialization' => 'Installation',
                'experience_years' => 8,
                'availability' => 'Full-time',
                'certifications' => 'Solar Panel Installation Cert, Electrical Safety Cert',
                'status' => 'active',
                'performance' => 95,
                'pending_tasks' => 3,
                'completed_tasks' => 125,
                'monthly_tasks_completed' => 42,
                'ontime_rate' => 89,
                'quality_score' => 94,
                'activities' => [
                    [
                        'title' => 'System Installation',
                        'description' => 'Completed installation of 5.5 kWp system',
                        'date' => 'Oct 20, 2025',
                        'status' => 'completed',
                        'details' => '5.5 kWp'
                    ],
                    [
                        'title' => 'Panel Cleaning',
                        'description' => 'Performed quarterly maintenance',
                        'date' => 'Oct 18, 2025',
                        'status' => 'completed',
                        'details' => 'Maintenance'
                    ]
                ]
            ];

            $data = [
                'user' => $this->user,
                'managerType' => 'operation_managers',
                'manager' => $sample_manager
            ];

            $this->view('pages/installer_admin/manager_details', $data, layout: 'dashboard');
        }

        public function inventory_managers_detail($managerId = null){
            // Sample inventory manager detail data
            $sample_manager = [
                'id' => $managerId ?? 1,
                'name' => 'David Wilson',
                'email' => 'david.w@solarsense.com',
                'contact' => '+94 77 234 5678',
                'nic' => '987654321V',
                'address' => '78 Warehouse Ave, Colombo 1',
                'district' => 'Colombo',
                'warehouse' => 'Main Warehouse - Colombo',
                'status' => 'active',
                'efficiency' => 92,
                'low_stock' => 3,
                'total_orders' => 156,
                'inventory_items' => 245,
                'low_stock_threshold' => 10,
                'last_inventory_check' => 'Oct 21, 2025',
                'stock_accuracy' => 96,
                'fulfillment_rate' => 98,
                'warehouse_efficiency' => 92,
                'activities' => [
                    [
                        'title' => 'Stock Replenishment',
                        'description' => 'Received 50 solar panels from supplier',
                        'date' => 'Oct 20, 2025',
                        'status' => 'completed',
                        'details' => '50 units'
                    ],
                    [
                        'title' => 'Inventory Check',
                        'description' => 'Quarterly inventory verification',
                        'date' => 'Oct 19, 2025',
                        'status' => 'completed',
                        'details' => '245 items'
                    ]
                ]
            ];

            $data = [
                'user' => $this->user,
                'managerType' => 'inventory_managers',
                'manager' => $sample_manager
            ];

            $this->view('pages/installer_admin/manager_details', $data, layout: 'dashboard');
        }

        // Add Manager
        public function add_manager($managerType = 'operation_managers'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form submission
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'user' => $this->user,
                    'managerType' => $_POST['managerType'] ?? $managerType,
                    'mode' => 'add',
                    'fullName' => trim($_POST['fullName'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'contactNumber' => trim($_POST['contactNumber'] ?? ''),
                    'nic' => trim($_POST['nic'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'district' => trim($_POST['district'] ?? ''),
                    'joinDate' => trim($_POST['joinDate'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
                    'confirmPassword' => trim($_POST['confirmPassword'] ?? ''),
                    'status' => trim($_POST['status'] ?? 'Active'),
                    'experienceLevel' => trim($_POST['experienceLevel'] ?? ''),
                    'certifications' => trim($_POST['certifications'] ?? ''),
                    'emergencyContactName' => trim($_POST['emergencyContactName'] ?? ''),
                    'emergencyContactNumber' => trim($_POST['emergencyContactNumber'] ?? ''),
                    
                    // Error fields
                    'fullName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'district_err' => '',
                    'joinDate_err' => '',
                    'password_err' => '',
                    'confirmPassword_err' => '',
                ];

                // Add operation manager specific fields
                if($managerType === 'operation_managers'){
                    $data['specialization'] = trim($_POST['specialization'] ?? '');
                    $data['teamSize'] = trim($_POST['teamSize'] ?? '');
                    $data['specialization_err'] = '';
                    $data['teamSize_err'] = '';
                }

                // Add inventory manager specific fields
                if($managerType === 'inventory_managers'){
                    $data['warehouseLocation'] = trim($_POST['warehouseLocation'] ?? '');
                    $data['warehouseCapacity'] = trim($_POST['warehouseCapacity'] ?? '');
                    $data['managedCategories'] = trim($_POST['managedCategories'] ?? '');
                    $data['warehouseLocation_err'] = '';
                    $data['warehouseCapacity_err'] = '';
                }

                // Validate fields
                if(empty($data['fullName'])){
                    $data['fullName_err'] = 'Please enter full name';
                }

                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                    $data['email_err'] = 'Please enter valid email';
                }

                if(empty($data['contactNumber'])){
                    $data['contactNumber_err'] = 'Please enter contact number';
                }

                if(empty($data['nic'])){
                    $data['nic_err'] = 'Please enter NIC/ID number';
                }

                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                } elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }

                if(empty($data['confirmPassword'])){
                    $data['confirmPassword_err'] = 'Please confirm password';
                } elseif($data['password'] !== $data['confirmPassword']){
                    $data['confirmPassword_err'] = 'Passwords do not match';
                }

                if(empty($data['district'])){
                    $data['district_err'] = 'Please select district';
                }

                if(empty($data['joinDate'])){
                    $data['joinDate_err'] = 'Please select join date';
                }

                // Validate operation manager specific fields
                if($managerType === 'operation_managers'){
                    if(empty($data['specialization'])){
                        $data['specialization_err'] = 'Please select specialization';
                    }
                    if(empty($data['teamSize'])){
                        $data['teamSize_err'] = 'Please enter team size';
                    }
                }

                // Validate inventory manager specific fields
                if($managerType === 'inventory_managers'){
                    if(empty($data['warehouseLocation'])){
                        $data['warehouseLocation_err'] = 'Please enter warehouse location';
                    }
                    if(empty($data['warehouseCapacity'])){
                        $data['warehouseCapacity_err'] = 'Please enter warehouse capacity';
                    }
                }

                // Check if there are any errors
                $hasErrors = false;
                foreach($data as $key => $value){
                    if(strpos($key, '_err') !== false && !empty($value)){
                        $hasErrors = true;
                        break;
                    }
                }

                if(!$hasErrors){
                    // TODO: Add manager to database
                    // For now, just redirect with success message
                    flash('manager_message', 'Manager added successfully', 'alert alert-success');
                    redirect('installeradmin/managers/' . $managerType);
                } else {
                    // Load view with errors
                    $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
                }
            } else {
                // Show form
                $data = [
                    'user' => $this->user,
                    'managerType' => $managerType,
                    'mode' => 'add'
                ];

                $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
            }
        }

        // Edit Manager
        public function edit_manager($managerType, $managerId){
            // TODO: Implement edit manager functionality
            $data = [
                'user' => $this->user,
                'managerType' => $managerType,
                'managerId' => $managerId,
                'mode' => 'edit'
            ];

            $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
        }

        // Delete Manager
        public function delete_manager($managerType, $managerId){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // TODO: Delete manager from database
                flash('manager_message', 'Manager deleted successfully', 'alert alert-success');
                redirect('installeradmin/managers/' . $managerType);
            } else {
                redirect('installeradmin/managers/' . $managerType);
            }
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/installer_admin/profile', $data, layout: 'dashboard');
        }

        public function help(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/installer_admin/help', $data, 'dashboard');
        }
        
        
    }

?>
