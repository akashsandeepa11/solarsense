<?php

require_once APPROOT . '/helpers/Mail_Helper.php';

class SuperAdmin extends Controller
{

    private $authModel;
    private $fleetModel;
    private $dashboardModel;
    private $profileModel;
    private $installerAdminDashboard;
    private $helpModel;

    private $user = [
        'role' => ROLE_SUPER_ADMIN,
    ];

    public function __construct()
    {
        $this->authModel = $this->model('M_Auth');
        $this->fleetModel = $this->model('M_Installer_Fleet');
        $this->dashboardModel = $this->model('M_Superadmin_Dashboard');
        $this->profileModel = $this->model('M_Profile');
        $this->installerAdminDashboard = $this->model('M_InstallerAdmin_Dashboard');
        $this->helpModel = $this->model('M_Help');
    }

    public function dashboard()
    {
        $stats = $this->dashboardModel->getStats();
        $growth = $this->dashboardModel->getCompanyGrowthByYear();
        $user_type = $this->dashboardModel->getUserByType();
        $company_district = $this->dashboardModel->getCompanyByDistrict();
        $verification_requests = $this->dashboardModel->getVerificationRequests();

        $data = [
            'user' => $this->user,
            'stats' => $stats,
            'growth' => $growth,
            'user_type' => $user_type,
            'company_district' => $company_district,
            'verification_requests' => $verification_requests
        ];

        $this->view('pages/super_admin/dashboard', $data, layout: 'dashboard');
    }

    public function companies($page = 'dashboard', $id = null)
    {
        $companies = $this->fleetModel->getAllCompaniesWithStats();
        // Routing for sub-actions
        if ($page === 'add')
            return $this->add_company();
        if ($page === 'edit')
            return $this->edit_company($id);
        if ($page === 'delete')
            return $this->delete_company($id);
        if ($page === 'details')
            return $this->company_details($id);

        $data = [
            'user' => $this->user,
            'companies' => $companies
        ];
        $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
    }

    public function add_company()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user' => $this->user,
                'company_name' => trim($_POST['company_name']),
                'address' => trim($_POST['address']),
                'number_of_employees' => trim($_POST['number_of_employees']),
                'website' => trim($_POST['website']),
                'district' => trim($_POST['district']),
                'postal_code' => trim($_POST['postal_code']),
                'contact_number' => trim($_POST['contact_number']),
                'email' => trim($_POST['email']),
                'service_type' => trim($_POST['service_type']),
                'years_of_experience' => trim($_POST['years_of_experience']),
                'completed_projects' => trim($_POST['completed_projects']),
                'service_areas' => trim($_POST['service_areas']),
                'company_name_err' => '',
                'email_err' => '' // Add other error keys as needed
            ];

            // Basic Validation
            if (empty($data['company_name']))
                $data['company_name_err'] = 'Company name is required';
            if ($this->model('M_Auth')->findUserByEmail($data['email']))
                $data['email_err'] = 'Email already exists';

            if (empty($data['company_name_err']) && empty($data['email_err'])) {
                $result = $this->model('M_Auth')->add_verified_company($data);
                if ($result) {
                    // Send Credentials Email
                    require_once APPROOT . '/helpers/Mail_Helper.php';
                    sendWelcomeEmail($data['email'], $data['company_name'], $result['password'], generateWelcomeResetUrl($result['user_id']));

                    setToast('Company added and verified successfully!', 'success');
                    redirect('superadmin/companies');
                }
            }
            $this->view('pages/super_admin/add_company', $data, layout: 'dashboard');
        } else {
            $data = ['user' => $this->user];
            $this->view('pages/super_admin/add_company', $data, layout: 'dashboard');
        }
    }

    public function edit_company($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'company_id' => $id,
                'company_name' => trim($_POST['company_name']),
                'address' => trim($_POST['address']),
                'number_of_employees' => trim($_POST['number_of_employees']),
                'website' => trim($_POST['website']),
                'district' => trim($_POST['district']),
                'postal_code' => trim($_POST['postal_code']),
                'contact_number' => trim($_POST['contact_number']),
                'email' => trim($_POST['email']),
                'service_type' => trim($_POST['service_type']),
                'years_of_experience' => trim($_POST['years_of_experience']),
                'completed_projects' => trim($_POST['completed_projects']),
                'service_areas' => trim($_POST['service_areas']),
                'company_name_err' => ''
            ];

            if (empty($data['company_name']))
                $data['company_name_err'] = 'Company name is required';

            if (empty($data['company_name_err'])) {
                // Use fleetModel consistently as defined in your constructor
                if ($this->fleetModel->update_company($data)) {
                    setToast('Company details updated successfully', 'success');
                    redirect('superadmin/companies');
                } else {
                    setToast('Update failed. Please try again.', 'error');
                }
            }
            $this->view('pages/super_admin/add_company', $data, layout: 'dashboard');
        } else {
            $company = $this->fleetModel->get_company_by_id($id);

            if (!$company) {
                setToast('Company not found.', 'error');
                redirect('superadmin/companies');
                return;
            }

            $data = [
                'user' => $this->user,
                'company_id' => $company->company_id,
                'company_name' => $company->company_name,
                'address' => $company->address,
                'number_of_employees' => $company->num_employees,
                'website' => $company->website,
                'district' => $company->district,
                'postal_code' => $company->postal_code,
                'contact_number' => $company->contact,
                'email' => $company->email,
                'service_type' => $company->service_type,
                'years_of_experience' => $company->years_experience,
                'completed_projects' => $company->complete_projects,
                'service_areas' => $company->service_areas
            ];

            $this->view('pages/super_admin/add_company', $data, layout: 'dashboard');
        }
    }


    public function delete_company($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->fleetModel->delete_company($id)) {
                setToast('Company and associated user removed.', 'success');
            } else {
                setToast('Failed to delete company.', 'error');
            }
            redirect('superadmin/companies');
        }
    }

    public function company_details($id = null)
    {
        if (empty($id)) {
            setToast('Invalid company ID', 'error');
            redirect('superadmin/companies');
            return;
        }

        $company = $this->fleetModel->get_company_details($id);

        if (!$company) {
            setToast('Company not found or access denied', 'error');
            redirect('superadmin/companies');
            return;
        }

        $data = [
            'user' => $this->user,
            'company' => $company
        ];

        $this->view('pages/super_admin/company_details', $data, layout: 'dashboard');
    }

    public function reports()
    {
        $data = [
            'user'        => $this->user,
            'report_data' => [
                'platform_overview'  => $this->dashboardModel->getStats(),
                'user_role_data'     => $this->dashboardModel->getUserByType(),
                'company_growth'     => $this->dashboardModel->getCompanyGrowthByYear(),
                'company_district'   => $this->dashboardModel->getCompanyByDistrict(),
                'companies_report'   => $this->installerAdminDashboard->getAllCompaniesWithStats(),
                'verifications'      => $this->fleetModel->get_verifications(),
            ]
        ];

        $this->view('pages/super_admin/reports', $data, 'main');
    }


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
        error_log("Verifying company with ID: " . $companyId);

        $result = $this->fleetModel->verify_company((int) $companyId);

        if (!$result) {
            setToast('Verification failed', 'error');
            redirect('superadmin/verification');
            return;
        }

        // Send welcome email using the helper function
        $newUserId = (int) ($result['user_id'] ?? 0);
        $resetUrl = $newUserId ? generateWelcomeResetUrl($newUserId) : '';
        $mailSent = sendWelcomeEmail(
            $result['email'],   // to
            $result['email'],   // username
            $result['password'], // plaintext password
            $resetUrl            // set-password link
        );

        if (!$mailSent) {
            // Verification succeeded but email failed — still redirect with a warning
            setToast('Company verified, but email could not be sent.', 'warning');
        } else {
            setToast('Company verified & credentials emailed', 'success');
        }

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
        $complaints = $this->helpModel->get_all_complaints();
        $data = [
            'user' => $this->user,
            'complaints' => $complaints
        ];

        $this->view('pages/super_admin/complaints', $data, 'dashboard');
    }

    public function resolve_ticket($id = null)
    {
        if (empty($id)) {
            redirect('superadmin/complaints');
            return;
        }

        $helpModel = $this->model('M_Help');

        if ($helpModel->resolve_complaint($id)) {
            setToast('Ticket marked as resolved!', 'success');
        } else {
            setToast('Failed to resolve ticket.', 'error');
        }

        redirect('superadmin/complaints');
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $user_data = $this->profileModel->getSuperadminProfile($userId);
        $data = [
            'user' => $this->user,
            'user_data' => $user_data,
            'user_id' => $userId
        ];

        $this->view('pages/super_admin/profile', $data, 'dashboard');
    }
}

?>