<?php

    class ServiceAgent extends Controller{

        private $user = [
            'role' => ROLE_SERVICE_AGENT,
        ];

        private $taskModel;
        private $historyModel;


        public function __construct(){
            $this->taskModel = $this->model('M_maintenance_task');
            $this->historyModel = $this->model('M_maintenance_history');
        }

        public function tasks(){

            $tasks = $this->taskModel->get_agent_tasks();
            
            $data = [
                'user'  => $this->user,
                'tasks' => $tasks,
            ];
            
            $this->view('pages/service_agent/task', $data, layout: 'dashboard');
        }

        /**
         * AJAX endpoint: update task status
         * POST /serviceagent/update_task_status
         * Body: task_id, status
         */
        public function update_task_status(){
            header('Content-Type: application/json');

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $task_id = intval($_POST['task_id'] ?? 0);
            $status  = trim($_POST['status'] ?? '');

            $allowed = ['Pending', 'In Progress', 'Completed'];
            if(!$task_id || !in_array($status, $allowed)){
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
                exit;
            }

            $result = $this->taskModel->update_task_status($task_id, $status);
            echo json_encode(['success' => (bool)$result]);
            exit;
        }

        public function history(){

            $history = $this->historyModel->get_agent_history(); 

            $data = [
                'user'    => $this->user,
                'history' => $history,
            ];
                    
            $this->view('pages/service_agent/history', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/profile', $data, 'dashboard');
        }

        public function reports(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/service_agent/reports', $data, 'dashboard');
        }

        public function help(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/help', $data, 'dashboard');
        }

         public function report(){
            $data = [
                'user' => $this->user,
            ];

            $this->view('pages/service_agent/report', $data, 'dashboard');
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
