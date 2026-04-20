<?php

class OperationManager extends Controller
{

    private $fleetModel;
    private $teamModel;
    private $taskModel;
    private $inventoryModel;
    private $notificationModel;
    private $notifications = [];
    private $reportModel;
    private $profileModel;

    private $user = [
        'role' => ROLE_OPERATION_MANAGER,
    ];

    public function __construct()
    {
        $this->fleetModel = $this->model('M_Fleet');
        $this->teamModel = $this->model('M_Team');
        $this->taskModel = $this->model('M_maintenance_task');
        $this->inventoryModel = $this->model('M_inventory');
        $this->notificationModel = $this->model('M_Notification');
        $this->reportModel = $this->model('M_maintenance_report');
        $this->profileModel = $this->model('M_Profile');

        // Pre-load notifications for the topnavbar bell on every page
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $this->notifications = $userId
            ? $this->notificationModel->get_notifications($userId, 10)
            : [];
    }

    // --- Admin-Specific Pages ---

    // In class OperationManager (app/controllers/OperationManager.php)
    public function dashboard($page = 'dashboard')
    {
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // Debugging: Echo the company ID as requested
        echo "";

        if (!$companyId) {
            setToast('Unauthorized access. Company ID not found.', 'error');
            redirect('auth/login');
            return;
        }

        // Load the dashboard model
        $dashboardModel = $this->model('M_InstallerAdmin_Dashboard');

        // Fetch the same data used by the Installer Admin
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'stats' => $dashboardModel->getStats($companyId),
            'alerts' => $dashboardModel->getAlerts($companyId),
            'performance_snapshot' => $dashboardModel->getPerformanceSnapshot($companyId),
            'service_agents' => $dashboardModel->getServiceTeamStatus($companyId),
            'new_customers_data' => $dashboardModel->getNewCustomersChartData($companyId),
            'service_tasks_data' => $dashboardModel->getServiceTasksChartData($companyId),
        ];

        // Handle system performance sub-page
        if ($page == 'system_performance') {
            return $this->view('pages/common/system_performance', $data, layout: 'dashboard');
        }

        // Load the shared common dashboard view
        $this->view('pages/common/dashboard', $data, layout: 'dashboard');
    }

    public function fleet($page = 'dashboard', $customerId = null)
    {
        if ($page === 'customer_details') {
            return $this->customerdetails($customerId);
        }

        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // FIX: Use get_customer_stats to return ARRAYS and prevent the stdClass error
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'customers' => $this->fleetModel->get_customer_stats($companyId),
            'stats' => [
                'total_clients' => $this->fleetModel->get_total_customers($companyId) ?? 0,
                'pending_maintenace' => $this->fleetModel->get_pending_services($companyId) ?? 0,
                'completed_services' => $this->fleetModel->get_completed_services($companyId) ?? 0,
            ],
        ];

        $this->view('pages/common/fleet_dashboard', $data, layout: 'dashboard');
    }

    public function team($page = 'dashboard', $agentId = null)
    {
        if ($page === 'agent_details') {
            return $this->agent_details($agentId);
        }

        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // FIX: Use get_service_agent_stats to align with InstallerAdmin logic
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'agents' => $this->teamModel->get_service_agent_stats($companyId),
            'stats' => [
                'total_agents' => $this->teamModel->get_total_agents($companyId)->total_agents ?? 0,
                'active_agents' => $this->teamModel->get_active_agents($companyId)->active_agents ?? 0,
                'total_tasks' => $this->teamModel->get_total_tasks($companyId)->total_tasks ?? 0,
                'pending_tasks' => $this->teamModel->get_pending_tasks($companyId)->pending_tasks ?? 0,
            ],
        ];

        $this->view('pages/common/team', $data, layout: 'dashboard');
    }

    public function customerdetails($customerId = null)
    {
        if (empty($customerId)) {
            setToast('Invalid customer ID', 'error');
            redirect('operationmanager/fleet');
            return;
        }

        // 1. Get company context for the logged-in manager
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // 2. Fetch detailed customer and system object
        $customer = $this->fleetModel->get_customer_details_by_company($customerId, $companyId);

        // 3. Fetch service agents assigned to this specific homeowner
        $serviceAgents = $this->teamModel->get_service_agents_by_customer($customerId);

        if (!$customer) {
            setToast('Customer not found or access denied', 'error');
            redirect('operationmanager/fleet');
            return;
        }

        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'customerId' => $customerId,
            'customer' => $customer,
            'service_agents' => $serviceAgents,
        ];

        // Render the shared customer details view
        $this->view('pages/common/customer_details', $data, layout: 'dashboard');
    }

    /**
     * View detailed information for a specific service agent
     */
    public function agent_details($agentId = null)
    {
        if (empty($agentId)) {
            setToast('Invalid agent ID', 'error');
            redirect('operationmanager/team');
            return;
        }

        // 1. Get company context for the logged-in manager
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        if (!$companyId) {
            setToast('Unauthorized access.', 'error');
            redirect('auth/login');
            return;
        }

        // 2. Fetch specific agent details using the model
        $agent = $this->teamModel->get_agent_details_by_id($agentId, $companyId);

        if (!$agent) {
            setToast('Agent not found or access denied.', 'error');
            redirect('operationmanager/team');
            return;
        }

        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'agent' => $agent,
        ];

        // Render the shared agent details view
        $this->view('pages/common/agent_details', $data, layout: 'dashboard');
    }

    public function quotation($action = null, $id = null)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);
        $quotationModel = $this->model('M_Quotation');

        // Handle Actions (Approve/Delete)
        if ($action === 'approve' && $id) {
            if ($quotationModel->update_status($id, 'Approved')) {
                setToast('Quotation approved successfully!', 'success');
            }
            redirect('operationmanager/quotation');
            return;
        }

        if ($action === 'delete' && $id) {
            if ($quotationModel->delete_quotation($id)) {
                setToast('Quotation removed.', 'success');
            }
            redirect('operationmanager/quotation');
            return;
        }

        // Fetch dynamic data
        $quotationsRaw = $quotationModel->get_quotations_by_company($companyId);

        // Convert to array for view compatibility
        $quotations = json_decode(json_encode($quotationsRaw), true);

        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'quotations' => $quotations
        ];

        $this->view('pages/operation_manager/quotation', $data, layout: 'dashboard');
    }

    /**
     * Unified Maintenance and Purchases Management
     */
    public function maintenance($tab = 'tasks', $id = 'all', $action = null)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $companyId = $this->teamModel->get_company_id_by_user($userId);

        // --- Handle POST Actions for Maintenance Tasks ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($tab === 'tasks') {
                if ($id === 'create') {
                    $taskData = [
                        'homeowner_id' => $_POST['homeowner_id'] ?? null,
                        'service_type_id' => $_POST['service_type_id'] ?? null,
                        'service_description' => trim($_POST['service_description'] ?? ''),
                    ];
                    if ($taskData['homeowner_id'] && $taskData['service_type_id'] && $this->taskModel->create_task($taskData)) {
                        setToast('Task created successfully.', 'success');
                    } else {
                        setToast('Failed to create task.', 'error');
                    }
                }

                if ($id === 'assign' && $action) {
                    $agentId = $_POST['agent_id'] ?? null;
                    if ($agentId && $this->taskModel->assign_agent($action, $agentId)) {
                        setToast('Agent assigned successfully.', 'success');
                    }
                }

                if ($id === 'delete' && $action) {
                    if ($this->taskModel->delete_task($action)) {
                        setToast('Task deleted successfully.', 'success');
                    }
                }
                // Redirect back to the task tab
                redirect('operationmanager/maintenance/tasks');
                return;
            } else {
                if ($id === 'assign' && $action) {
                    $agentId = $_POST['agent_id'] ?? null;

                    if ($agentId && $this->inventoryModel->assign_agent($action, $agentId)) {
                        setToast('Agent assigned successfully.', 'success');
                    } else {
                        setToast('Cannot assign agent.', 'error');
                    }
                }
                redirect('operationmanager/maintenance/purchases/all');
                return;
            }
        }

        // --- Prepare Data for View ---
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'active_tab' => $tab,
        ];

        if ($tab === 'purchases') {
            $data['orders'] = $this->inventoryModel->get_all_orders($companyId);

            $data['stats'] = $this->inventoryModel->get_order_stats($companyId);
            $data['agents'] = $this->taskModel->get_active_agents($companyId);
            $data['customers'] = $this->fleetModel->get_customer_stats($companyId);
            $data['status_filter'] = 'all';

            $this->view('pages/common/purchases', $data, layout: 'dashboard');

        } elseif ($tab === 'reports') {
            // Handle "View Detail" sub-routing: maintenance/reports/view/{report_id}
            if ($id === 'view' && $action) {
                $report = $this->reportModel->get_report_details($action);
                if (!$report) {
                    setToast('Report not found.', 'error');
                    redirect('operationmanager/maintenance/reports');
                }
                $data['report'] = $report;
                return $this->view('pages/operation_manager/view_report', $data, layout: 'dashboard');
            }

            // Default List View for Reports
            $data['reports'] = $this->reportModel->get_reports_by_company($companyId);
            $this->view('pages/operation_manager/reports_list', $data, layout: 'dashboard');
        } else {
            // Handle Maintenance Tasks
            $data['tasks'] = $this->taskModel->get_tasks_by_company($companyId);
            // $data['agents'] = $this->teamModel->get_service_agent_stats($companyId);
            $data['agents'] = $this->taskModel->get_active_agents($companyId);
            $data['customers'] = $this->fleetModel->get_customer_stats($companyId);
            $data['service_types'] = $this->taskModel->get_service_types();

            $this->view('pages/operation_manager/maintenance', $data, layout: 'dashboard');
        }
    }

    public function reports()
    {
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
        ];

        $this->view('pages/operation_manager/reports', $data, layout: 'dashboard');
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $companyId = $this->teamModel->get_company_id_by_user($userId);
        $profileData = $this->profileModel->getOperationManagerProfile($userId, $companyId);
        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
            'profileData' => $profileData
        ];

        $this->view('pages/operation_manager/profile', $data, layout: 'dashboard');
    }

    // --- Notifications (full page) ---
    public function notifications()
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);

        $data = [
            'user' => $this->user,
            'notifications' => $this->notificationModel->get_all_notifications($userId),
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    // --- Clear all notifications (AJAX) ---
    public function clearNotifications()
    {
        header('Content-Type: application/json');
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $success = $userId ? $this->notificationModel->delete_all($userId) : false;
        echo json_encode(['success' => $success]);
        exit();
    }

    public function help()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $helpModel = $this->model('M_Help');
            $userId = $_SESSION['user_id'];

            $data = [
                'user_id' => $userId,
                'type' => $this->user['role'],
                'full_name' => $_SESSION['user_name'] ?? 'Operation Manager',
                'title' => trim($_POST['title']), // Capture title from view
                'description' => trim($_POST['notes']), // Map 'notes' from view to 'description'
            ];

            if ($helpModel->add_complaint($data)) {
                setToast('Support request submitted!', 'success');
                redirect('operationmanager/help');
            } else {
                setToast('Database error. Please try again.', 'error');
            }
        }

        $data = [
            'user' => $this->user,
            'notifications' => $this->notifications,
        ];
        $this->view('pages/operation_manager/help', $data, layout: 'dashboard');
    }


}

?>