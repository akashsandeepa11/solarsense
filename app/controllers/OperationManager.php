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

         public function team($page = 'dashboard', $agentId = null){
            
            if($page === 'agent_details'){
                return $this->agent_details($agentId);
            }
            
            $data = [
                'user' => $this->user,
                'agents' => $this->teamModel->get_service_agents_by_company(1)
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
        
        
    }

?>
