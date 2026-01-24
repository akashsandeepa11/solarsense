<?php

require_once APPROOT . '/controllers/Mail.php';

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


    }

    public function reports()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/reports', $data, 'dashboard');
    }

    // public function add_installer_verification(): void
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         // Initial load
    //         $this->view('pages/auth/installer_registration', [
    //             'user' => $this->user
    //         ], layout: 'main');
    //         return;
    //     }

    //     $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    //     // Collect form data (MATCH FORM NAMES)
    //     $data = [
    //         'user' => $this->user,

    //         'company_name' => trim($_POST['company_name'] ?? ''),
    //         'address' => trim($_POST['address'] ?? ''),
    //         'number_of_employees' => trim($_POST['number_of_employees'] ?? ''),
    //         'website' => trim($_POST['website'] ?? ''),
    //         'district' => trim($_POST['district'] ?? ''),
    //         'postal_code' => trim($_POST['postal_code'] ?? ''),
    //         'contact_number' => trim($_POST['contact_number'] ?? ''),
    //         'email' => trim($_POST['email'] ?? ''),
    //         'service_type' => trim($_POST['service_type'] ?? ''),
    //         'years_of_experience' => trim($_POST['years_of_experience'] ?? ''),
    //         'completed_projects' => trim($_POST['completed_projects'] ?? ''),
    //         'service_areas' => trim($_POST['service_areas'] ?? ''),

    //         // Error fields
    //         'company_name_err' => '',
    //         'email_err' => '',
    //         'contact_number_err' => '',
    //         'address_err' => '',
    //         'district_err' => '',
    //         'service_type_err' => ''
    //     ];

    //     // Validation
    //     if (empty($data['company_name'])) {
    //         $data['company_name_err'] = 'Company name is required';
    //     }

    //     if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    //         $data['email_err'] = 'Valid email is required';
    //     } elseif ($this->authModel->findUserByEmail($data['email'])) {
    //         $data['email_err'] = 'Email is already registered';
    //     }

    //     if (empty($data['contact_number'])) {
    //         $data['contact_number_err'] = 'Contact number is required';
    //     }

    //     if (empty($data['address'])) {
    //         $data['address_err'] = 'Address is required';
    //     }

    //     if (empty($data['district'])) {
    //         $data['district_err'] = 'District is required';
    //     }

    //     if (empty($data['service_type'])) {
    //         $data['service_type_err'] = 'Service type is required';
    //     }

    //     // Check for errors
    //     foreach ($data as $key => $value) {
    //         if (str_ends_with($key, '_err') && !empty($value)) {
    //             $this->view('pages/auth/installer_registration', $data, layout: 'main');
    //             return;
    //         }
    //     }

    //     // Save to DB (PASS ALL REQUIRED FIELDS)
    //     if ($this->fleetModel->add_installer_verification($data)) {
    //         setToast('Request submitted successfully', 'success');
    //         redirect('auth/installerRegistrationHandler');
    //         return;
    //     }

    //     setToast('Something went wrong during registration.', 'error');
    //     $this->view('pages/auth/installer_registration', $data, layout: 'main');
    // }


    // --- Notifications ---
    public function notifications()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    // function to verify customer
    public function verify_company($companyId)
    {
        $result = $this->fleetModel->verify_company((int) $companyId); //calling verify_company from model M_Installer_Fleet

        if (!$result) {
            setToast('Verification failed', 'error');
            redirect('super_admin/verification');
            return;
        }

        // Send email
        $mail = new Mail();
        $mail->sendWelcomeEmail(
            $result['email'],       // email
            $result['email'],       // username
            $result['password']     // PLAINTEXT password
        );

        setToast('Company verified & credentials emailed', 'success');
        redirect('super_admin/verification');
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