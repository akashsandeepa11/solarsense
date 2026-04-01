<?php
class M_maintenance_task {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all tasks (service requests) assigned to the currently logged-in service agent.
     * Joins service_type for the task title and user (homeowner) for the customer name.
     *
     * @return array - Array of task objects or empty array on failure
     */
    public function get_agent_tasks() {
        try {
            $this->db->query("
                SELECT
                    sr.task_id,
                    st.type_name         AS title,
                    sr.service_description AS notes,
                    u.full_name          AS customer,
                    h.address            AS address,
                    h.contact     AS contact_number,
                    sr.request_date      AS date,
                    sr.status,
                    sr.homeowner_id,
                    sr.agent_id
                FROM service_req sr
                LEFT JOIN service_type st ON sr.service_type_id = st.service_type_id
                LEFT JOIN user u          ON sr.homeowner_id    = u.user_id
                LEFT JOIN homeowner h     ON sr.homeowner_id = h.user_id
                WHERE sr.agent_id = :agent_id  AND sr.status <> 'Completed' 
                ORDER BY sr.request_date DESC
            ");
            $this->db->bind(':agent_id', $_SESSION['user_id'] ?? 0);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('M_maintenance_task::get_agent_tasks failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update the status of a service request.
     *
     * @param int    $task_id - The task ID to update
     * @param string $status  - New status value: 'Pending' | 'In Progress' | 'Completed'
     * @return bool
     */
    public function update_task_status($task_id, $status) {
        try {
            $this->db->query('
                UPDATE service_req
                SET status = :status
                WHERE task_id = :task_id
                  AND agent_id = :agent_id
            ');
            $this->db->bind(':status',   $status);
            $this->db->bind(':task_id',  $task_id);
            $this->db->bind(':agent_id', $_SESSION['user_id'] ?? 0);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_maintenance_task::update_task_status failed: ' . $e->getMessage());
            return false;
        }
    }
}
?>
