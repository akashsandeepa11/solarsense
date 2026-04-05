<?php

    class OperationManager extends Controller{

        private $fleetModel;
        private $teamModel;

        private $user = [
            'role' => ROLE_OPERATION_MANAGER,
        ];
        
        public function __construct(){
            $this->fleetModel = $this->model('M_Fleet');
            $this->teamModel = $this->model('M_Team');
            // Here you would add logic to ensure only an admin can access these methods.
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

        public function maintenance(){
            $data = [
                'user' => $this->user,
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
