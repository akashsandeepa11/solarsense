<?php
class M_maintenance_task {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all service requests for a company (joins homeowner → user → service_type → agent)
     */
    public function get_tasks_by_company($companyId) {
        try {
            $this->db->query("
                SELECT
                    sr.task_id,
                    sr.service_description,
                    sr.request_date,
                    sr.status,
                    sr.agent_id,
                    sr.homeowner_id,
                    st.type_name        AS service_type,
                    u.full_name         AS customer_name,
                    h.address           AS customer_address,
                    h.contact           AS customer_contact,
                    ag.full_name        AS agent_name
                FROM service_req sr
                LEFT JOIN service_type st ON sr.service_type_id = st.service_type_id
                LEFT JOIN homeowner h     ON sr.homeowner_id    = h.user_id
                LEFT JOIN user u          ON sr.homeowner_id    = u.user_id
                LEFT JOIN user ag         ON sr.agent_id        = ag.user_id
                WHERE h.company_id = :company_id
                ORDER BY sr.request_date DESC
            ");
            $this->db->bind(':company_id', $companyId);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('get_tasks_by_company failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all service types for the create-task dropdown
     */
    public function get_service_types() {
        try {
            $this->db->query("SELECT service_type_id, type_name FROM service_type ORDER BY type_name ASC");
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('get_service_types failed: ' . $e->getMessage());
            return [];
        }
    }
    // for dropdown of agents when assigning task
    public function get_active_agents($companyId) {
        $this->db->query("
            SELECT 
                sa.user_id,
                u.full_name
            FROM service_agent sa
            JOIN user u ON sa.user_id = u.user_id
            WHERE sa.company_id = :company_id
            AND sa.status = 'Active'
        ");
        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }

    /**
     * Create a new service request
     */
    public function create_task($data) {
        try {
            $this->db->query("
                INSERT INTO service_req
                    (homeowner_id, service_type_id, service_description, status, request_date)
                VALUES
                    (:homeowner_id, :service_type_id, :service_description, 'Pending', NOW())
            ");
            $this->db->bind(':homeowner_id',        $data['homeowner_id']);
            $this->db->bind(':service_type_id',     $data['service_type_id']);
            $this->db->bind(':service_description', $data['service_description'] ?? '');
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('create_task failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Assign a service agent to a task and set status to Pending
     */
    public function assign_agent($taskId, $agentId) {
    
        try {
            $this->db->query("
                UPDATE service_req
                SET agent_id = :agent_id,
                    status   = 'Pending'
                WHERE task_id = :task_id
            ");
            $this->db->bind(':agent_id', $agentId);
            $this->db->bind(':task_id',  $taskId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('assign_agent failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a service request by task_id
     */
    public function delete_task($taskId) {
        try {
            $this->db->query("DELETE FROM service_req WHERE task_id = :task_id");
            $this->db->bind(':task_id', $taskId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('delete_task failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get agent's own active tasks (for Service Agent role)
     */
    public function get_agent_tasks() {
        try {
            $this->db->query("
                SELECT
                    sr.task_id,
                    st.type_name           AS title,
                    sr.service_description AS notes,
                    u.full_name            AS customer,
                    h.address              AS address,
                    h.contact              AS contact_number,
                    sr.request_date        AS date,
                    sr.status,
                    sr.homeowner_id,
                    sr.agent_id
                    o.agent_id
                FROM service_req sr
                LEFT JOIN service_type st ON sr.service_type_id = st.service_type_id
                LEFT JOIN user u          ON sr.homeowner_id    = u.user_id
                LEFT JOIN homeowner h     ON sr.homeowner_id    = h.user_id
                WHERE sr.agent_id = :agent_id AND sr.status <> 'Completed'
                ORDER BY sr.request_date DESC
            ");
            $this->db->bind(':agent_id', $_SESSION['user_id'] ?? 0);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('get_agent_tasks failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update status (used by Service Agent)
     */
    public function update_task_status($task_id, $status) {
        try {
            $this->db->query("
                UPDATE service_req
                SET status = :status
                WHERE task_id  = :task_id
                  AND agent_id = :agent_id
            ");
            $this->db->bind(':status',   $status);
            $this->db->bind(':task_id',  $task_id);
            $this->db->bind(':agent_id', $_SESSION['user_id'] ?? 0);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('update_task_status failed: ' . $e->getMessage());
            return false;
        }
    }
}
?>
