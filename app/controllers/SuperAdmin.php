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

    public function companies()
    {

        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/super_admin/companies', $data, layout: 'dashboard');
    }

    public function reports()
    {
        $data = [
            'user' => $this->user,
            'report_data' => [
                'platform_overview' => $this->dashboardModel->getStats(),
                'user_role_data' => $this->dashboardModel->getUserByType(),
                'company_growth' => $this->dashboardModel->getCompanyGrowthByYear(),
                'company_district' => $this->dashboardModel->getCompanyByDistrict(),
                'companies_report' => $this->installerAdminDashboard->getAllCompaniesWithStats()
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
        $mailSent = sendWelcomeEmail(
            $result['email'],   // to
            $result['email'],   // username
            $result['password'] // plaintext password
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

    public function profile()
    {
        $user_data = $this->profileModel->getSuperadminProfile($_SESSION['user_id']);
        $data = [
            'user' => $this->user,
            'user_data' => $user_data
        ];

        $this->view('pages/super_admin/profile', $data, 'dashboard');
    }
}

?>