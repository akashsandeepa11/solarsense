<?php

class ServiceAgent extends Controller
{

    private $user = [
        'role' => ROLE_SERVICE_AGENT,
    ];

    private $taskModel;
    private $historyModel;
    private $reportModel;
    private $teamModel;
    private $profileModel;



    public function __construct()
    {
        $this->taskModel = $this->model('M_maintenance_task');
        $this->historyModel = $this->model('M_maintenance_history');
        $this->reportModel = $this->model('M_maintenance_report');
        $this->teamModel         = $this->model('M_Team');
        $this->profileModel      = $this->model('M_Profile');
    }

    public function tasks($page = '', $task_id = null)
    {

        if ($page === 'maintenance_report' && $task_id) {
            $this->maintenance_report($task_id);
            return;
        }

        $tasks = $this->taskModel->get_agent_tasks();

        $data = [
            'user' => $this->user,
            'tasks' => $tasks,
        ];

        $this->view('pages/service_agent/task', $data, layout: 'dashboard');
    }

    /**
     * AJAX endpoint: update task status
     * POST /serviceagent/update_task_status
     * Body: task_id, status
     */
    public function update_task_status()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $task_id = intval($_POST['task_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowed = ['Pending', 'In Progress', 'Completed'];
        if (!$task_id || !in_array($status, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }

        $result = $this->taskModel->update_task_status($task_id, $status);
        echo json_encode(['success' => (bool) $result]);
        exit;
    }

    public function history()
    {

        $history = $this->historyModel->get_agent_history();

        $data = [
            'user' => $this->user,
            'history' => $history,
        ];

        $this->view('pages/service_agent/history', $data, layout: 'dashboard');
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $companyId = $this->teamModel->get_company_id_by_user($userId);
        $profileData = $this->profileModel->getServiceAgentProfile($userId, $companyId);
        $data = [
            'user'          => $this->user,
            'profileData' => $profileData
        ];
        $this->view('pages/service_agent/profile', $data, layout: 'dashboard');
    }

    public function update_profile()
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $companyId = $this->teamModel->get_company_id_by_user($userId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user' => $this->user,
                'fullName' => trim($_POST['fullName'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'contactNumber' => trim($_POST['contactNumber'] ?? ''),
                'address' => trim($_POST['physicalAddress'] ?? ''),
                'district' => trim($_POST['district'] ?? ''),
                'company_name' => trim($_POST['company-name'] ?? ''),
                'register_date' => trim($_POST['register-date'] ?? ''),
                'specialization' => trim($_POST['specialization'] ?? ''),
                'status' => trim($_POST['status'] ?? 'Active'),
        ];
        $success = $this->profileModel->updateServiceAgentProfile($userId, $data);
        if ($success) {
                    setToast('Manager Updated Successfully', 'success');
                    redirect('serviceagent/profile');
                    return;
                }
                setToast('Something went wrong during update', 'error');

        $this->view('pages/service_agent/profile', $data, layout: 'dashboard');
        }
    }

    public function reports()
    {
        $tasks   = $this->taskModel->get_agent_tasks()       ?? [];
        $history = $this->historyModel->get_agent_history()  ?? [];

        $data = [
            'user'    => $this->user,
            'tasks'   => $tasks,
            'history' => $history,
        ];

        $this->view('pages/service_agent/reports', $data, 'dashboard');
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
                'full_name' => $_SESSION['user_name'] ?? 'Service Agent',
                'title' => trim($_POST['title']), // Capture title from view
                'description' => trim($_POST['notes']), // Map 'notes' from view to 'description'
            ];

            if ($helpModel->add_complaint($data)) {
                setToast('Support request submitted!', 'success');
                redirect('serviceagent/help');
            } else {
                setToast('Database error. Please try again.', 'error');
            }
        }

        $data = ['user' => $this->user];
        $this->view('pages/service_agent/help', $data, layout: 'dashboard');
    }


    public function maintenance_report($task_id)
    {

        $isSubmitted = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_form_submitted']);

        $data = [
            'user' => $this->user,
            'task_id' => $task_id,
            'actions_taken' => '',
            'time_spent' => '',
            'final_status' => '',
            'replaced_parts' => '',
            'technician_notes' => '',
            'completion_date' => date('Y-m-d'),
            'actions_taken_err' => '',
            'time_spent_err' => '',
            'final_status_err' => '',
            'replaced_parts_err' => '',
            'technician_notes_err' => '',
            'completion_date_err' => '',
        ];

        if ($isSubmitted) {
            $data['actions_taken'] = trim($_POST['actions_taken'] ?? '');
            $data['time_spent'] = floatval($_POST['time_spent'] ?? 0);
            $data['final_status'] = trim($_POST['final_status'] ?? '');
            $data['replaced_parts'] = trim($_POST['replaced_parts'] ?? '');
            $data['technician_notes'] = trim($_POST['technician_notes'] ?? '');
            $data['completion_date'] = trim($_POST['completion_date'] ?? '');

            if ($data['actions_taken'] === '') {
                $data['actions_taken_err'] = 'Actions taken is required';
            }

            if ($data['time_spent'] <= 0) {
                $data['time_spent_err'] = 'Time spent must be greater than 0';
            }

            if ($data['final_status'] === '') {
                $data['final_status_err'] = 'Final status is required';
            }

            if ($data['completion_date'] === '') {
                $data['completion_date_err'] = 'Completion date is required';
            }

            if (
                empty($data['actions_taken_err']) &&
                empty($data['time_spent_err']) &&
                empty($data['final_status_err']) &&
                empty($data['completion_date_err'])
            ) {
                $reportData = [
                    'task_id' => $task_id,
                    'agent_id' => $_SESSION['user_id'] ?? null,
                    'actions_taken' => $data['actions_taken'],
                    'replaced_parts' => $data['replaced_parts'],
                    'time_spent' => $data['time_spent'],
                    'completion_date' => date('Y-m-d', strtotime($data['completion_date'])),
                    'technician_notes' => $data['technician_notes'],
                    'final_status' => $data['final_status'],
                ];

                if ($this->reportModel->insertReportAndCompleteTask($reportData)) {
                    setToast('Report submitted successfully!', 'success');
                    redirect('serviceagent/tasks');
                }

                setToast('Failed to submit report. Please try again.', 'error');
            } else {
                setToast('Please fill in all required fields.', 'error');
            }
        }

        $this->view('pages/service_agent/report', $data, 'dashboard');


    }

    // --- Notifications ---
    public function notifications()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

}


?>