<?php

class SuperAdmin extends Controller
{

    private $authModel;
    private $fleetModel;

    private $user = [
        'role' => ROLE_SUPER_ADMIN,
    ];

    public function __construct()
    {
        $this->authModel = $this->model('M_Auth');
        $this->fleetModel = $this->model('M_Installer_Fleet');
    }

    public function dashboard()
    {

        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/dashboard', $data, layout: 'dashboard');
    }

    public function companies()
    {

        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
    }

    public function fleet($page = 'dashboard', $customerId = null)
    {
        $data = [
            'user' => $this->user,
        ];

        if ($page === 'add_customer') {
            return $this->add_customer();
        }

        if ($page === 'customer_details') {
            return $this->customerdetails($customerId);
        }

        if ($page === 'view') {
            return $this->view_customer();
        }

        if ($page === 'edit_customer') {
            return $this->edit_customer();
        }

        if ($page === 'delete_customer') {
            return $this->delete_customer();
        }

        $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
    }


    // public function verification(){
    //     $data = [
    //         'user' => $this->user,
    //     ];

    //     $this->view('pages/super_admin/verification', $data, 'dashboard');
    // }

    public function add_installer_verification(): void
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form is submitting
            // Validate the data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Input data from form
            $data = [
                'user' => $this->user,
                'company_name' => trim($_POST['company_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'contact' => trim($_POST['contact'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),

                // Error fields
                'company_name_err' => '',
                'email_err' => '',
                'contact_err' => '',
                'address_err' => ''
            ];

            // Validate all fields
            if ($this->authModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already registered';
            }

            if (empty($data['company_name'])) {
                $data['company_name_err'] = "Please enter full name";
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = "Please enter a valid email address";
            }

            if (empty($data['contact']) || !preg_match('/^[0-9\-\+\s\(\)]+$/', $data['contact'])) {
                $data['contact_err'] = "Please enter a valid contact number";
            }

            if (empty($data['address'])) {
                $data['address_err'] = "Please enter physical address";
            }

            // Check for any errors
            $hasErrors = !empty($data['company_name_err']) || !empty($data['email_err']) ||
                !empty($data['contact_err']) || !empty($data['address_err']);

            if ($hasErrors) {
                // Reload form with errors
                $this->view('pages/auth/installer_registration', $data, layout: 'main');
                return;
            }

            // All validation passed - save to database

            var_dump($data);
            $prospectiveInstallerData = [
                'company_name' => $data['company_name'],
                'address' => $data['address'],
                'contact' => $data['contact'],
                'email' => $data['email']
            ];

            // Call model to save data
            if ($this->fleetModel->add_installer_verification($prospectiveInstallerData)) {
                setToast('Request Submitted Successfully', 'success');
                redirect('auth/installerRegistrationHandler');
            } else {
                setToast('Something went wrong during registration.', 'error');
                $this->view('pages/auth/installer_registration', $data, layout: 'main');
            }
            return;

        } else {
            // Initial form load
            $data = [
                'user' => $this->user,
                'company_name' => '',
                'email' => '',
                'contact' => '',
                'address' => '',

                'company_name_err' => '',
                'email_err' => '',
                'contact_err' => '',
                'address_err' => ''
            ];

            $this->view('pages/auth/installer_registration', $data, layout: 'main');
        }
    }

    public function verify_company($companyId)
    {
        $companyId = (int) $companyId;
        $this->fleetModel->verify_company($companyId);
        setToast('Request Submitted Successfully', 'success');
        redirect('superadmin/verification');

    }

    public function verification()
    {
        $verifications = $this->fleetModel->get_verifications();
        $data = [
            'user' => $this->user,
            'verifications' => $verifications
        ];
        $this->view('pages/super_admin/verification', $data, 'dashboard');
    }


    public function complaints()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/complaints', $data, 'dashboard');
    }

    public function profile()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/profile', $data, 'dashboard');
    }
}

?>