<?php

    class SuperAdmin extends Controller{

        private $authModel;
        private $fleetModel;

        private $user = [
            'role' => ROLE_SUPER_ADMIN,
        ];

        public function __construct(){
            $this->authModel = $this->model('M_Auth');
            $this->fleetModel = $this->model('M_Installer_Fleet');
            echo "hiyfvtuvut";
        }

        public function dashboard(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/super_admin/dashboard', $data, layout: 'dashboard');
        }

        public function companies(){

            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
        }

        // public function verification(){
        //     $data = [
        //         'user' => $this->user,
        //     ];

        //     $this->view('pages/super_admin/verification', $data, 'dashboard');
        // }

        public function add_propective_installer(): void{
            
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Form is submitting
                // Validate the data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Input data from form
                $data = [
                    'user' => $this->user,
                    'companyName' => trim($_POST['companyName'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'contactNumber' => trim($_POST['contactNumber'] ?? ''),
                    'physicalAddress' => trim($_POST['physicalAddress'] ?? ''),

                    // Error fields
                    'companyName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'physicalAddress_err' => ''
                ];

                // Validate all fields
                if ($this->authModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already registered';
                }

                if(empty($data['companyName'])){
                    $data['companyName_err'] = "Please enter full name";
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

                // Check for any errors
                $hasErrors = !empty($data['companyName_err']) || !empty($data['email_err']) || 
                             !empty($data['contactNumber_err']) || !empty($data['physicalAddress_err']);

                if($hasErrors){
                    // Reload form with errors
                    $this->view('pages/auth/installer_registration', $data, layout: 'dashboard');
                    return;
                }

                // All validation passed - save to database

                $prospectiveInstallerData = [
                    'full_name' => $data['companyName'],
                    'address' => $data['physicalAddress'],
                    'contact' => $data['contactNumber']
                ];

                // Call model to save data
                if($this->fleetModel->add_prospective_installer($prospectiveInstallerData)) {
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
                    'companyName' => '',
                    'email' => '',
                    'contactNumber' => '',
                    'physicalAddress' => '',

                    'companyName_err' => '',
                    'email_err' => '',
                    'contactNumber_err' => '',
                    'physicalAddress_err' => ''
                ];

                $this->view('pages/super_admin/verification', $data, layout: 'dashboard');
            }
        }


        public function complaints(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/complaints', $data, 'dashboard');
        }
        
        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/super_admin/profile', $data, 'dashboard');
        }
    }

?>
