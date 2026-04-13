<?php

require_once APPROOT . '/helpers/Mail_Helper.php';

class InstallerAdmin extends Controller
{
    private $fleetModel;
    private $authModel;
    private $teamModel;
    private $managerModel;

    private $user = [
        'role' => ROLE_INSTALLER_ADMIN,

    ];

    public function __construct()
    {
        $this->fleetModel = $this->model('M_Fleet');
        $this->authModel = $this->model('M_Auth');
        $this->teamModel = $this->model('M_Team');
        $this->managerModel = $this->model('M_Manager');
    }

    public function dashboard($page = 'dashboard')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            redirect('pages/login');
            return;
        }

        $dashboardModel = $this->model('M_InstallerAdmin_Dashboard');

        $data = [
            'user' => $this->user,
            'stats' => $dashboardModel->getStats($companyId),
            'alerts' => $dashboardModel->getAlerts($companyId), 
            'performance_snapshot' => $dashboardModel->getPerformanceSnapshot($companyId), 
            'service_agents' => $dashboardModel->getServiceTeamStatus($companyId), 
            'new_customers_data' => $dashboardModel->getNewCustomersChartData($companyId), 
            'service_tasks_data' => $dashboardModel->getServiceTasksChartData($companyId) // Fetch task status data
        ];

        if ($page == 'system_performance') {
            return $this->view('pages/common/system_performance', $data, layout: 'dashboard');
        }

        $this->view('pages/installer_admin/dashboard', $data, layout: 'dashboard');
    }

    // --- Notifications ---
    public function notifications()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    // --- Reports ---
    public function reports()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/installer_admin/reports', $data, layout: 'dashboard');
    }

    // --- Fleet Management ---
    public function fleet($page = 'dashboard', $customerId = null)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // Fetch statistics (Returns integers from the model)
        $total_clients = $this->fleetModel->get_total_customers($companyId);
        $pending_maintenance = $this->fleetModel->get_pending_services($companyId);
        $completed_services = $this->fleetModel->get_completed_services($companyId);

        // Routing for sub-pages
        if ($page === 'add_customer')
            return $this->add_customer();
        if ($page === 'customer_details')
            return $this->customerdetails($customerId);
        if ($page === 'edit_customer')
            return $this->edit_customer($customerId);
        if ($page === 'delete_customer')
            return $this->delete_customer($customerId);

        $data = [
            'user' => $this->user,
            'customers' => $this->fleetModel->get_customer_by_company($companyId),
            'stats' => [
                'total_clients' => $total_clients ?? 0,
                'pending_maintenace' => $pending_maintenance ?? 0,
                'completed_services' => $completed_services ?? 0
            ]
        ];

        $this->view('pages/common/fleet_dashboard', $data, layout: 'dashboard');
    }

    public function customerdetails($customerId = null)
    {
        if (empty($customerId)) {
            setToast('Invalid customer ID', 'error');
            redirect('installeradmin/fleet');
            return;
        }

        $customer      = $this->fleetModel->get_customer_details($customerId);
        $serviceAgents = $this->teamModel->get_service_agents_by_customer($customerId);

        if (!$customer) {
            setToast('Customer not found', 'error');
            redirect('installeradmin/fleet');
            return;
        }

        $data = [
            'user'           => $this->user,
            'customerId'     => $customerId,
            'customer'       => $customer,
            'service_agents' => $serviceAgents,
        ];

        $this->view('pages/common/customer_details', $data, layout: 'dashboard');
    }

    public function add_customer(): void
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                'district' => trim($_POST['district'] ?? ''),
                'systemCapacity' => trim($_POST['systemCapacity'] ?? ''),
                'panelTilt' => trim($_POST['panelTilt'] ?? ''),
                'panelAzimuth' => trim($_POST['panelAzimuth'] ?? ''),
                'installationDate' => trim($_POST['installationDate'] ?? ''),
                'panelBrand' => trim($_POST['panelBrand'] ?? ''),
                'inverterBrand' => trim($_POST['inverterBrand'] ?? ''),
                'moduleType' => trim($_POST['moduleType'] ?? ''),
                'arrayType' => trim($_POST['arrayType'] ?? ''),
                'lossesPCT' => trim($_POST['lossesPCT'] ?? ''),
                'dcAcRatio' => trim($_POST['dcAcRatio'] ?? ''),
                'invEffPCT' => trim($_POST['invEffPCT'] ?? ''),
                'cebAccount' => trim($_POST['cebAccount'] ?? ''),

                // Error fields
                'fullName_err' => '',
                'email_err' => '',
                'contactNumber_err' => '',
                'physicalAddress_err' => '',
                'nic_err' => '',
                'district_err' => '',
                'systemCapacity_err' => '',
                'panelTilt_err' => '',
                'panelAzimuth_err' => '',
                'installationDate_err' => '',
                'panelBrand_err' => '',
                'inverterBrand_err' => '',
                'moduleType_err' => '',
                'arrayType_err' => '',
                'lossesPCT_err' => '',
                'dcAcRatio_err' => '',
                'invEffPCT_err' => '',
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
            if (empty($data['fullName'])) {
                $data['fullName_err'] = "Please enter full name";
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = "Please enter a valid email address";
            } else {
                // Check email is already registed or not
                if ($this->authModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already registered';
                }
            }

            if (empty($data['contactNumber']) || !preg_match('/^[0-9\-\+\s\(\)]+$/', $data['contactNumber'])) {
                $data['contactNumber_err'] = "Please enter a valid contact number";
            }

            if (empty($data['physicalAddress'])) {
                $data['physicalAddress_err'] = "Please enter physical address";
            }

            if (empty($data['nic'])) {
                $data['nic_err'] = "Please enter NIC/ID number";
            }

            if (empty($data['district'])) {
                $data['district_err'] = "Please select a district";
            }

            if (empty($data['systemCapacity']) || !is_numeric($data['systemCapacity'])) {
                $data['systemCapacity_err'] = "Please enter valid system capacity";
            }

            if (empty($data['panelTilt']) || !is_numeric($data['panelTilt'])) {
                $data['panelTilt_err'] = "Please enter valid panel tilt";
            }

            if (empty($data['panelAzimuth']) || !is_numeric($data['panelAzimuth'])) {
                $data['panelAzimuth_err'] = "Please select valid panel azimuth";
            }

            if (empty($data['installationDate'])) {
                $data['installationDate_err'] = "Please select installation date";
            }

            if (empty($data['panelBrand'])) {
                $data['panelBrand_err'] = "Please select panel brand";
            }

            if (empty($data['inverterBrand'])) {
                $data['inverterBrand_err'] = "Please select inverter brand";
            }

            if (empty($data['moduleType']) || !is_numeric($data['moduleType'])) {
                $data['moduleType_err'] = "Please select module type";
            }

            if (empty($data['arrayType']) || !is_numeric($data['arrayType'])) {
                $data['arrayType_err'] = "Please select array type";
            }

            if (empty($data['lossesPCT']) || !is_numeric($data['lossesPCT'])) {
                $data['lossesPCT_err'] = "Please enter losses percentage";
            }

            if (empty($data['dcAcRatio']) || !is_numeric($data['dcAcRatio'])) {
                $data['dcAcRatio_err'] = "Please enter DC/AC ratio";
            }

            if (empty($data['invEffPCT']) || !is_numeric($data['invEffPCT'])) {
                $data['invEffPCT_err'] = "Please enter inverter efficiency percentage";
            }

            if (empty($data['cebAccount']) || !is_numeric($data['cebAccount'])) {
                $data['cebAccount_err'] = "Please enter CEB account number";
            }

            // Check for any errors
            $hasErrors = !empty($data['fullName_err']) || !empty($data['email_err']) ||
                !empty($data['contactNumber_err']) || !empty($data['physicalAddress_err']) ||
                !empty($data['nic_err']) || !empty($data['district_err']) ||
                !empty($data['systemCapacity_err']) || !empty($data['panelTilt_err']) ||
                !empty($data['panelAzimuth_err']) || !empty($data['installationDate_err']) ||
                !empty($data['panelBrand_err']) || !empty($data['inverterBrand_err']) || !empty($data['moduleType_err']) ||
                !empty($data['arrayType_err']) || !empty($data['lossesPCT_err']) ||
                !empty($data['dcAcRatio_err']) || !empty($data['invEffPCT_err']) ||
                !empty($data['cebAccount_err']);

            if ($hasErrors) {
                // Reload form with errors
                $this->view('pages/installer_admin/add_customer', $data, layout: 'dashboard');
                return;
            }

            // All validation passed - save to database
            $userData = [
                'email' => $data['email']
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
                'module_type' => $data['moduleType'],
                'array_type' => $data['arrayType'],
                'losses_pct' => $data['lossesPCT'],
                'dc_ac_ratio' => $data['dcAcRatio'],
                'inv_eff_pct' => $data['invEffPCT']
            ];
            // Call model to save data
            $createResult = $this->fleetModel->add_customer($userData, $customerData, $panelData);
            if ($createResult && is_array($createResult) && !empty($createResult['success'])) {
                // Send credentials email with set-password link
                $plainPassword = $createResult['password'] ?? '';
                $recipientEmail = $createResult['email'] ?? $userData['email'];
                $newUserId  = (int) ($createResult['user_id'] ?? 0);
                $resetUrl   = $newUserId ? generateWelcomeResetUrl($newUserId) : '';
                $mailSent = sendWelcomeEmail($recipientEmail, $recipientEmail, $plainPassword, $resetUrl);

                if ($mailSent) {
                    setToast('Customer Added Successfully', 'success');
                } else {
                    setToast('Customer added but failed to send email.', 'warning');
                }

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

    public function edit_customer($customerId = null)
    {
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
        if (!empty($customerId)) {
            $customerData = $this->fleetModel->get_customer_details($customerId);

            if ($customerData) {
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            if (empty($data['fullName'])) {
                $data['fullName_err'] = "Please enter full name";
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = "Please enter a valid email address";
            }

            if (empty($data['contactNumber']) || !preg_match('/^[0-9\-\+\s\(\)]+$/', $data['contactNumber'])) {
                $data['contactNumber_err'] = "Please enter a valid contact number";
            }

            if (empty($data['physicalAddress'])) {
                $data['physicalAddress_err'] = "Please enter physical address";
            }

            if (empty($data['nic'])) {
                $data['nic_err'] = "Please enter NIC/ID number";
            }

            if (empty($data['district'])) {
                $data['district_err'] = "Please select a district";
            }

            // Password is optional in edit mode, but must match if provided
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    $data['password_err'] = "Password must be at least 6 characters";
                }
                if ($data['password'] !== $data['confirmPassword']) {
                    $data['confirmPassword_err'] = "Passwords do not match";
                }
            }

            if (empty($data['systemCapacity']) || !is_numeric($data['systemCapacity'])) {
                $data['systemCapacity_err'] = "Please enter valid system capacity";
            }

            if (empty($data['panelTilt']) || !is_numeric($data['panelTilt'])) {
                $data['panelTilt_err'] = "Please enter valid panel tilt";
            }

            if (empty($data['panelAzimuth']) || !is_numeric($data['panelAzimuth'])) {
                $data['panelAzimuth_err'] = "Please select valid panel azimuth";
            }

            if (empty($data['installationDate'])) {
                $data['installationDate_err'] = "Please select installation date";
            }

            if (empty($data['panelBrand'])) {
                $data['panelBrand_err'] = "Please select panel brand";
            }

            if (empty($data['inverterBrand'])) {
                $data['inverterBrand_err'] = "Please select inverter brand";
            }

            if (empty($data['cebAccount'])) {
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

            if ($hasErrors) {
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
            if (!empty($data['password'])) {
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
            if ($this->fleetModel->update_customer($data['customerId'], $userData, $customerData, $panelData)) {
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

    public function delete_customer($customerId = null)
    {
        // Only process POST requests for deletion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('installeradmin/fleet');
            return;
        }

        if (empty($customerId)) {
            setToast('Invalid customer ID', 'error');
            redirect('installeradmin/fleet');
            return;
        }

        // Call model to delete customer
        if ($this->fleetModel->delete_customer($customerId)) {
            redirect('installeradmin/fleet');
            setToast('Customer Deleted Successfully', 'success');
        } else {
            setToast('Failed to delete customer. Please try again.', 'error');
        }

        redirect('installeradmin/fleet');
    }


    // --- Team Management ---
    public function team($page = 'dashboard', $agentId = null)
    {
        // 1. Get the current logged-in user's company ID
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            setToast('Unauthorized access or company not found.', 'error');
            redirect('pages/login');
            return;
        }

        // 2. Routing logic for sub-pages
        if ($page === 'add_service_agent') {
            return $this->add_service_agent();
        }
        if ($page === 'agent_details') {
            return $this->agent_details($agentId);
        }
        if ($page === 'edit_agent') {
            return $this->edit_agent($agentId);
        }
        if ($page === 'delete_agent') {
            return $this->delete_agent($agentId);
        }

        // 3. Fetch statistics using the companyId
        $total_agents_row = $this->teamModel->get_total_agents($companyId);
        $active_agents_row = $this->teamModel->get_active_agents($companyId);
        $total_tasks_row = $this->teamModel->get_total_tasks($companyId);
        $pending_tasks_row = $this->teamModel->get_pending_tasks($companyId);

        // 4. Prepare data for the view
        $data = [
            'user' => $this->user,
            'agents' => $this->teamModel->get_service_agents_by_company($companyId),
            'stats' => [
                'total_agents' => $total_agents_row->total_agents ?? 0,
                'active_agents' => $active_agents_row->active_agents ?? 0,
                'total_tasks' => $total_tasks_row->total_tasks ?? 0,
                'pending_tasks' => $pending_tasks_row->pending_tasks ?? 0
            ]
        ];

        $this->view('pages/common/team', $data, layout: 'dashboard');
    }

    public function add_service_agent()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                'district_err' => '',
                'specialization_err' => '',
                'experienceYears_err' => '',
                'availability_err' => '',
                'certifications_err' => ''
            ];

            // Validate Full Name
            if (empty($data['fullName'])) {
                $data['fullName_err'] = 'Full Name is required';
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Email is required';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }

            // Validate Contact Number
            if (empty($data['contactNumber'])) {
                $data['contactNumber_err'] = 'Contact Number is required';
            } elseif (!preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $data['contactNumber'])) {
                $data['contactNumber_err'] = 'Please enter a valid phone number';
            }

            // Validate NIC
            if (empty($data['nic'])) {
                $data['nic_err'] = 'NIC/ID Number is required';
            }

            // Validate Address
            if (empty($data['address'])) {
                $data['address_err'] = 'Address is required';
            }

            // Validate District
            if (empty($data['district'])) {
                $data['district_err'] = 'District is required';
            }

            // Validate Specialization
            if (empty($data['specialization'])) {
                $data['specialization_err'] = 'Specialization is required';
            }

            // Validate Experience Years
            if (empty($data['experienceYears'])) {
                $data['experienceYears_err'] = 'Years of Experience is required';
            } elseif (!is_numeric($data['experienceYears']) || $data['experienceYears'] < 0) {
                $data['experienceYears_err'] = 'Please enter a valid number';
            }

            // Validate Availability
            if (empty($data['availability'])) {
                $data['availability_err'] = 'Availability is required';
            }

            // Check for validation errors
            if (
                empty($data['fullName_err']) && empty($data['email_err']) && empty($data['contactNumber_err']) &&
                empty($data['nic_err']) && empty($data['address_err']) && empty($data['district_err']) &&
                empty($data['specialization_err']) && empty($data['experienceYears_err']) && empty($data['availability_err'])
            ) {

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

                // Add creation date only for new agents
                if ($data['mode'] === 'add') {
                    $agentData['created_date'] = date('Y-m-d H:i:s');
                }

                // Prepare user data for model
                $userData = [
                    'email' => $data['email']
                ];

                // Call model to save data
                if ($data['mode'] === 'add') {
                    // Check if email already exists
                    if ($this->authModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'Email is already registered';
                        $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                        return;
                    }

                    // Check if NIC already exists
                    if ($this->teamModel->nic_exists($data['nic'])) {
                        $data['nic_err'] = 'NIC/ID Number is already registered';
                        $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                        return;
                    }

                    $createResult = $this->teamModel->add_service_agent($userData, $agentData);
                    if ($createResult && is_array($createResult) && !empty($createResult['success'])) {
                        // Send credentials email with set-password link
                        $plainPassword  = $createResult['password'] ?? '';
                        $recipientEmail = $createResult['email'] ?? $data['email'];
                        $newUserId      = (int) ($createResult['user_id'] ?? 0);
                        $resetUrl       = $newUserId ? generateWelcomeResetUrl($newUserId) : '';
                        $mailSent = sendWelcomeEmail($recipientEmail, $recipientEmail, $plainPassword, $resetUrl);

                        if ($mailSent) {
                            setToast('Service Agent Added Successfully', 'success');
                        } else {
                            setToast('Service Agent added but failed to send email.', 'warning');
                        }

                        redirect('installeradmin/team');
                    } else {
                        setToast('Failed to add service agent. Please try again.', 'error');
                        $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
                    }
                } else {
                    // Update mode
                    if ($this->teamModel->update_service_agent($data['agentId'], $data['agentId'], $userData, $agentData)) {
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
                'district_err' => '',
                'specialization_err' => '',
                'experienceYears_err' => '',
                'availability_err' => '',
                'certifications_err' => ''
            ];

            $this->view('pages/installer_admin/add_service_agent', $data, layout: 'dashboard');
        }
    }

    public function agent_details($agentId = null)
    {
        $data = [
            'user' => $this->user,
        ];

        // TODO: Fetch agent details from database using $agentId
        // For now, using sample data

        $this->view('pages/common/agent_details', $data, layout: 'dashboard');
    }

    public function edit_agent($agentId = null)
    {
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
        if (!empty($agentId)) {
            // Use the complete retrieval function for comprehensive data access
            $agentData = $this->teamModel->get_service_agent_complete($agentId);

            if ($agentData) {
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            if (empty($data['fullName'])) {
                $data['fullName_err'] = 'Full Name is required';
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Email is required';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }

            // Validate Contact Number
            if (empty($data['contactNumber'])) {
                $data['contactNumber_err'] = 'Contact Number is required';
            } elseif (!preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $data['contactNumber'])) {
                $data['contactNumber_err'] = 'Please enter a valid phone number';
            }

            // Validate NIC
            if (empty($data['nic'])) {
                $data['nic_err'] = 'NIC/ID Number is required';
            }

            // Validate Address
            if (empty($data['address'])) {
                $data['address_err'] = 'Address is required';
            }

            // Validate District
            if (empty($data['district'])) {
                $data['district_err'] = 'District is required';
            }

            // Validate Password (optional in edit mode, but must match if provided)
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be at least 6 characters';
                }
                if ($data['password'] !== $data['confirmPassword']) {
                    $data['confirmPassword_err'] = 'Passwords do not match';
                }
            }

            // Validate Specialization
            if (empty($data['specialization'])) {
                $data['specialization_err'] = 'Specialization is required';
            }

            // Validate Experience Years
            if (empty($data['experienceYears'])) {
                $data['experienceYears_err'] = 'Years of Experience is required';
            } elseif (!is_numeric($data['experienceYears']) || $data['experienceYears'] < 0) {
                $data['experienceYears_err'] = 'Please enter a valid number';
            }

            // Validate Availability
            if (empty($data['availability'])) {
                $data['availability_err'] = 'Availability is required';
            }

            // Check for validation errors
            if (
                empty($data['fullName_err']) && empty($data['email_err']) && empty($data['contactNumber_err']) &&
                empty($data['nic_err']) && empty($data['address_err']) && empty($data['district_err']) &&
                empty($data['password_err']) && empty($data['confirmPassword_err']) &&
                empty($data['specialization_err']) && empty($data['experienceYears_err']) && empty($data['availability_err'])
            ) {

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
                if ($this->teamModel->update_service_agent($data['agentId'], $userData, $agentData)) {
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

    public function delete_agent($agentId = null)
    {
        // Only process POST requests for deletion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('installeradmin/team');
            return;
        }

        if (empty($agentId)) {
            setToast('Invalid agent ID', 'error');
            redirect('installeradmin/team');
            return;
        }

        // Call model to delete service agent
        if ($this->teamModel->delete_service_agent($agentId)) {
            redirect('installeradmin/team');
            setToast('Service Agent Deleted Successfully', 'success');
        } else {
            setToast('Failed to delete service agent. Please try again.', 'error');
        }

        redirect('installeradmin/team');
    }


    public function managers($tab = 'operation_managers', $id = null, $action = null)
    {
        $total_op_managers = $this->managerModel->get_total_operation_managers();
        $active_tasks = $this->managerModel->get_active_service();
        $pending_tasks = $this->managerModel->get_pending_service();
        $completed_tasks = $this->managerModel->get_completed_service();

        $total_inv_managers = $this->managerModel->get_total_inventory_managers();
        $active_inv = $this->managerModel->get_inventory();
        $low_stock_items = $this->managerModel->get_low_stock_items();

        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            setToast('Unauthorized access or company not found.', 'error');
            redirect('pages/login');
            return;
        }

        // Handle add action
        if ($id === 'add') {
            return $this->add_manager($tab);
        }

        // Handle edit action
        if ($action === 'edit' && $id) {
            return $this->edit_manager($tab, $id);
        }

        // Handle delete action
        if ($action === 'delete' && $id) {
            return $this->delete_manager($tab, $id);
        }

        // Handle detail view
        if ($id) {
            if ($tab === 'operation_managers') {
                return $this->operation_managers_detail($id);
            } elseif ($tab === 'inventory_managers') {
                return $this->inventory_managers_detail($id);
            }
        }

        // Handle list views
        if ($tab === 'operation_managers') {
            return $this->operation_managers();
        } elseif ($tab === 'inventory_managers') {
            return $this->inventory_managers();
        }

        $data = [
            'user' => $this->user,
            'op_managers' => $this->managerModel->get_operation_manager_by_company_id($companyId),
            'inv_managers' => $this->managerModel->get_inventory_manager_by_company_id($companyId),
            'total_op_managers' => $total_op_managers,
            'active_tasks' => $active_tasks,
            'pending_tasks' => $pending_tasks,
            'completed_tasks' => $completed_tasks,
            'total_inv_managers' => $total_inv_managers,
            'active_inv' => $active_inv,
            'low_stock_items' => $low_stock_items
        ];


        redirect('installeradmin/managers/operation_managers');
    }

    // --- Manager Management ---
    // --- Manager Management ---
    public function operation_managers()
    {
        // 1. Get company context
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            redirect('pages/login');
            return;
        }

        // 2. Fetch list of managers from database
        $dbManagers = $this->managerModel->get_operation_manager_by_company_id($companyId);

        // 3. Prepare summary stats for the view
        $data = [
            'user' => $this->user,
            'managerType' => 'operation_managers',
            'managers' => [],
            'total_op_managers' => $this->managerModel->get_total_operation_managers(),
            'active_tasks' => $this->managerModel->get_active_service(),
            'pending_tasks' => $this->managerModel->get_pending_service(),
            'completed_tasks' => $this->managerModel->get_completed_service()
        ];

        // 4. Map database objects to the array format expected by managers_list.php
        foreach ($dbManagers as $manager) {
            $data['managers'][] = [
                'id' => $manager->user_id,
                'name' => $manager->full_name,
                'email' => $manager->email,
                'specialization' => ucfirst($manager->specialization),
                'district' => $manager->district,
                'status' => ucfirst($manager->status),
                'pending_tasks' => 0 // Placeholder: Requires per-manager task query
            ];
        }

        $this->view('pages/installer_admin/managers_list', $data, layout: 'dashboard');
    }

    public function inventory_managers()
    {
        // 1. Get company context
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            redirect('pages/login');
            return;
        }

        // 2. Fetch list of managers from database
        $dbManagers = $this->managerModel->get_inventory_manager_by_company_id($companyId);

        // 3. Prepare summary stats for the view
        $data = [
            'user' => $this->user,
            'managerType' => 'inventory_managers',
            'managers' => [],
            'total_inv_managers' => $this->managerModel->get_total_inventory_managers(),
            'active_inv' => $this->managerModel->get_inventory(),
            'low_stock_items' => $this->managerModel->get_low_stock_items()
        ];

        // 4. Map database objects to the array format expected by managers_list.php
        foreach ($dbManagers as $manager) {
            $data['managers'][] = [
                'id' => $manager->user_id,
                'name' => $manager->full_name,
                'email' => $manager->email,
                'warehouse' => $manager->warehouse_location,
                'status' => ucfirst($manager->status),
                'inventory_items' => 0, // Placeholder
                'low_stock' => 0,      // Placeholder
                'efficiency' => 0      // Placeholder
            ];
        }

        $this->view('pages/installer_admin/managers_list', $data, layout: 'dashboard');
    }

    // Manager Detail View Methods
    public function operation_managers_detail($managerId = null)
    {
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

    public function inventory_managers_detail($managerId = null)
    {
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
    public function add_manager($managerType)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                'specialization' => trim($_POST['specialization'] ?? ''),
                'experienceLevel' => trim($_POST['experienceLevel'] ?? ''),
                'teamSize' => trim($_POST['teamSize'] ?? ''),
                'status' => trim($_POST['status'] ?? 'Active'),
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
                'specialization_err' => '',
                'experienceLevel_err' => '',
                'teamSize_err' => '',
                'status_err' => ''
            ];

            // Add operation manager specific fields
            if ($managerType === 'operation_managers') {
                $data['specialization'] = trim($_POST['specialization'] ?? '');
                $data['teamSize'] = trim($_POST['teamSize'] ?? '');
                $data['experienceLevel'] = trim($_POST['experienceLevel'] ?? '');

                $data['specialization_err'] = '';
                $data['teamSize_err'] = '';
                $data['experienceLevel_err'] = '';
            }

            // Add inventory manager specific fields
            if ($managerType === 'inventory_managers') {
                $data['warehouseLocation'] = trim($_POST['warehouseLocation'] ?? '');
                $data['warehouseCapacity'] = trim($_POST['warehouseCapacity'] ?? '');
                $data['managedCategories'] = trim($_POST['managedCategories'] ?? '');
                $data['warehouseLocation_err'] = '';
                $data['warehouseCapacity_err'] = '';
            }

            // Validate fields
            if (empty($data['fullName'])) {
                $data['fullName_err'] = 'Please enter full name';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter valid email';
            }

            if (empty($data['contactNumber'])) {
                $data['contactNumber_err'] = 'Please enter contact number';
            }

            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter NIC/ID number';
            }

            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }

            if (empty($data['district'])) {
                $data['district_err'] = 'Please select district';
            }

            if (empty($data['joinDate'])) {
                $data['joinDate_err'] = 'Please select join date';
            }

            // Validate operation manager specific fields
            if ($managerType === 'operation_managers') {
                if (empty($data['specialization'])) {
                    $data['specialization_err'] = 'Please select specialization';
                }
                if (empty($data['teamSize'])) {
                    $data['teamSize_err'] = 'Please enter team size';
                }
                if (empty($data['experienceLevel'])) {
                    $data['experienceLevel_err'] = 'Please select experience level';
                }
                if (empty($data['status'])) {
                    $data['status_err'] = 'Please select status';
                }
                if (empty($data['emergencyContactName'])) {
                    $data['emergencyContactName_err'] = 'Please enter emergency contact name';
                }
                if (empty($data['emergencyContactNumber'])) {
                    $data['emergencyContactNumber_err'] = 'Please enter emergency contact number';
                }

            }

            // Validate inventory manager specific fields
            if ($managerType === 'inventory_managers') {
                if (empty($data['warehouseLocation'])) {
                    $data['warehouseLocation_err'] = 'Please enter warehouse location';
                }
                if (empty($data['warehouseCapacity'])) {
                    $data['warehouseCapacity_err'] = 'Please enter warehouse capacity';
                }
            }

            // Add creation date only for new managers
            if (($data['mode'] ?? 'add') === 'add') {
                $data['created_date'] = date('Y-m-d H:i:s');
            }

            // Prepare user data for model (don't overwrite $data used for the view/validation)
            $userData = [
                'email' => $data['email']
            ];

            // Check if there are any errors
            $hasErrors = false;
            foreach ($data as $key => $value) {
                if (strpos($key, '_err') !== false && !empty($value)) {
                    $hasErrors = true;
                    break;
                }
            }

            if (!$hasErrors) {
                // Call model to save data
                if ($data['mode'] === 'add') {
                    // Check if email already exists
                    if ($this->authModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'Email is already registered';
                        $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
                        return;
                    }

                    // Check if NIC already exists
                    if ($this->teamModel->nic_exists($data['nic'])) {
                        $data['nic_err'] = 'NIC/ID Number is already registered';
                        $this->view('pages/installer_admin/add_' . $managerType, $data, layout: 'dashboard');
                        return;
                    }

                    if ($managerType === 'operation_managers') {
                        $createResult = $this->managerModel->add_operation_manager($data);
                        if ($createResult && is_array($createResult) && !empty($createResult['success'])) {
                            // Send credentials email with set-password link
                            $plainPassword  = $createResult['password'] ?? '';
                            $recipientEmail = $createResult['email'] ?? $data['email'];
                            $newUserId      = (int) ($createResult['user_id'] ?? 0);
                            $resetUrl       = $newUserId ? generateWelcomeResetUrl($newUserId) : '';
                            $mailSent = sendWelcomeEmail($recipientEmail, $recipientEmail, $plainPassword, $resetUrl);

                            if ($mailSent) {
                                setToast('Operation Manager Added Successfully', 'success');
                            } else {
                                setToast('Operation Manager added but failed to send email.', 'warning');
                            }

                            redirect('installeradmin/managers/' . $managerType);
                        } else {
                            setToast('Failed to add operation manager. Please try again.', 'error');
                            $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
                        }
                    } elseif ($managerType === 'inventory_managers') {
                        $createResult = $this->managerModel->add_inventory_manager($data);
                        if ($createResult && is_array($createResult) && !empty($createResult['success'])) {
                            // Send credentials email with set-password link
                            $plainPassword  = $createResult['password'] ?? '';
                            $recipientEmail = $createResult['email'] ?? $data['email'];
                            $newUserId      = (int) ($createResult['user_id'] ?? 0);
                            $resetUrl       = $newUserId ? generateWelcomeResetUrl($newUserId) : '';
                            $mailSent = sendWelcomeEmail($recipientEmail, $recipientEmail, $plainPassword, $resetUrl);

                            if ($mailSent) {
                                setToast('Inventory Manager Added Successfully', 'success');
                            } else {
                                setToast('Inventory Manager added but failed to send email.', 'warning');
                            }

                            redirect('installeradmin/managers/' . $managerType);
                        } else {
                            setToast('Failed to add inventory manager. Please try again.', 'error');
                            $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
                        }
                    }

                }
                // else {
                //     // Update mode
                //     if ($this->teamModel->update_operation_manager($data)) {
                //         setToast('Operation Manager Updated Successfully', 'success');
                //         redirect('installeradmin/managers/' . $managerType);
                //     } else {
                //         setToast('Failed to update operation manager. Please try again.', 'error');
                //         $this->view('pages/installer_admin/add_manager', $data, layout: 'dashboard');
                //     }
                // }
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
    public function edit_manager($managerType, $managerId)
    {
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
    public function delete_manager($managerType, $managerId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // TODO: Delete manager from database
            flash('manager_message', 'Manager deleted successfully', 'alert alert-success');
            redirect('installeradmin/managers/' . $managerType);
        } else {
            redirect('installeradmin/managers/' . $managerType);
        }
    }

    public function profile()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/installer_admin/profile', $data, layout: 'dashboard');
    }

    public function help()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/installer_admin/help', $data, 'dashboard');
    }


}

?>