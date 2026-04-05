<?php

    class OperationManager extends Controller{

        private $fleetModel;
        private $teamModel;
        private $taskModel;

        private $user = [
            'role' => ROLE_OPERATION_MANAGER,
        ];
        
        public function __construct(){
            $this->fleetModel = $this->model('M_Fleet');
            $this->teamModel  = $this->model('M_Team');
            $this->taskModel  = $this->model('M_maintenance_task');
        }

        // --- Admin-Specific Pages ---

        public function dashboard(){
            $data = [
                'user' => $this->user,
            ];


            
            $this->view('pages/operation_manager/dashboard', $data, layout: 'dashboard');
        }

        public function fleet($page = 'dashboard', $customerId = null){

            if($page === 'customer_details'){
                return $this->customerdetails($customerId);
            }

            // Hardcoded company 1 for OM — replace with session lookup when OM company is wired up
            $companyId = 1;
            $data = [
                'user'      => $this->user,
                'customers' => $this->fleetModel->get_customer_by_company($companyId),
                'stats'     => [
                    'total_clients'      => $this->fleetModel->get_total_customers($companyId) ?? 0,
                    'pending_maintenace' => $this->fleetModel->get_pending_services($companyId) ?? 0,
                    'completed_services' => $this->fleetModel->get_completed_services($companyId) ?? 0,
                ]
            ];

            $this->view('pages/common/fleet_dashboard', $data, layout: 'dashboard');
        }

        public function customerdetails($customerId = null){
            if (empty($customerId)) {
                redirect('operationmanager/fleet');
                return;
            }

            $customer      = $this->fleetModel->get_customer_details($customerId);
            $serviceAgents = $this->teamModel->get_service_agents_by_customer($customerId);

            if (!$customer) {
                redirect('operationmanager/fleet');
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

        public function team($page = 'dashboard', $agentId = null){

            if($page === 'agent_details'){
                return $this->agent_details($agentId);
            }

            $companyId = 1;
            $data = [
                'user'   => $this->user,
                'agents' => $this->teamModel->get_service_agents_by_company($companyId),
                'stats'  => [
                    'total_agents'  => $this->teamModel->get_total_agents($companyId)->total_agents  ?? 0,
                    'active_agents' => $this->teamModel->get_active_agents($companyId)->active_agents ?? 0,
                    'total_tasks'   => $this->teamModel->get_total_tasks($companyId)->total_tasks     ?? 0,
                    'pending_tasks' => $this->teamModel->get_pending_tasks($companyId)->pending_tasks  ?? 0,
                ]
            ];

            $this->view('pages/common/team', $data, layout: 'dashboard');
        }

        public function agent_details($agentId = null){
            $data = [
                'user' => $this->user,
            ];
            
            // TODO: Fetch agent details from database using $agentId
            // For now, using sample data
            
            $this->view('pages/common/agent_details', $data, layout: 'dashboard');
        }

        public function quotation(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/quotation', $data, layout: 'dashboard');
        }

        public function maintenance($action = null, $id = null){
            $companyId = 1; // TODO: resolve from session once OM company is wired up

            // --- Handle POST actions ---
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if ($action === 'create') {
                    $taskData = [
                        'homeowner_id'       => $_POST['homeowner_id']       ?? null,
                        'service_type_id'    => $_POST['service_type_id']    ?? null,
                        'service_description'=> trim($_POST['service_description'] ?? ''),
                    ];
                    if ($taskData['homeowner_id'] && $taskData['service_type_id'] &&
                        $this->taskModel->create_task($taskData)) {
                        setToast('Task created successfully.', 'success');
                    } else {
                        setToast('Failed to create task. Check all fields.', 'error');
                    }
                    redirect('operationmanager/maintenance');
                    return;
                }

                if ($action === 'assign' && $id) {
                    $agentId = $_POST['agent_id'] ?? null;
                    if ($agentId && $this->taskModel->assign_agent($id, $agentId)) {
                        setToast('Agent assigned successfully.', 'success');
                    } else {
                        setToast('Failed to assign agent.', 'error');
                    }
                    redirect('operationmanager/maintenance');
                    return;
                }

                if ($action === 'delete' && $id) {
                    if ($this->taskModel->delete_task($id)) {
                        setToast('Task deleted successfully.', 'success');
                    } else {
                        setToast('Failed to delete task.', 'error');
                    }
                    redirect('operationmanager/maintenance');
                    return;
                }
            }

            // --- Load page data ---
            $data = [
                'user'          => $this->user,
                'tasks'         => $this->taskModel->get_tasks_by_company($companyId),
                'agents'        => $this->teamModel->get_service_agents_by_company($companyId),
                'customers'     => $this->fleetModel->get_customer_by_company($companyId),
                'service_types' => $this->taskModel->get_service_types(),
            ];

            $this->view('pages/operation_manager/maintenance', $data, layout: 'dashboard');
        }


        public function reports(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/reports', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/operation_manager/profile', $data, layout: 'dashboard');
        }

        // --- Notifications ---
        public function notifications(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/common/notifications', $data, layout: 'dashboard');
        }

        public function help(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/operation_manager/help', $data, 'dashboard');
        }
        
        
    }

?>
