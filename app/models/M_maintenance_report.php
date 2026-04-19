<?php

class M_maintenance_report
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Insert new report
    public function insertReport($data)
    {
        $this->db->query("
            INSERT INTO service_report 
            (task_id, agent_id, actions_taken, replaced_parts, time_spent, completion_date, technician_notes, final_status)
            VALUES 
            (:task_id, :agent_id, :actions_taken, :replaced_parts, :time_spent, :completion_date, :technician_notes, :final_status)
        ");

        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':agent_id', $data['agent_id']);
        $this->db->bind(':actions_taken', $data['actions_taken']);
        $this->db->bind(':replaced_parts', $data['replaced_parts']);
        $this->db->bind(':time_spent', $data['time_spent']);
        $this->db->bind(':completion_date', $data['completion_date']);
        $this->db->bind(':technician_notes', $data['technician_notes']);
        $this->db->bind(':final_status', $data['final_status']);

        return $this->db->execute();
    }

    public function insertReportAndCompleteTask($data)
    {
        try {
            $this->db->beginTransaction();

            $this->db->query("
                INSERT INTO service_report 
                (task_id, agent_id, actions_taken, replaced_parts, time_spent, completion_date, technician_notes, final_status)
                VALUES 
                (:task_id, :agent_id, :actions_taken, :replaced_parts, :time_spent, :completion_date, :technician_notes, :final_status)
            ");

            $this->db->bind(':task_id', $data['task_id']);
            $this->db->bind(':agent_id', $data['agent_id']);
            $this->db->bind(':actions_taken', $data['actions_taken']);
            $this->db->bind(':replaced_parts', $data['replaced_parts']);
            $this->db->bind(':time_spent', $data['time_spent']);
            $this->db->bind(':completion_date', $data['completion_date']);
            $this->db->bind(':technician_notes', $data['technician_notes']);
            $this->db->bind(':final_status', $data['final_status']);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }

            $this->db->query("
                UPDATE service_req
                SET status = 'Completed'
                WHERE task_id = :task_id
                  AND agent_id = :agent_id
            ");

            $this->db->bind(':task_id', $data['task_id']);
            $this->db->bind(':agent_id', $data['agent_id']);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('M_maintenance_report::insertReportAndCompleteTask failed: ' . $e->getMessage());
            return false;
        }
    }

    // OPTIONAL (future)
    public function getReportByTaskId($task_id)
    {
        $this->db->query("SELECT * FROM service_report WHERE task_id = :task_id");
        $this->db->bind(':task_id', $task_id);
        return $this->db->single();
    }

    public function get_reports_by_company($companyId)
    {
        try {
            $this->db->query("
            SELECT 
                r.report_id,
                r.task_id,
                r.completion_date,
                r.final_status,
                st.type_name AS service_type,
                u_cust.full_name AS customer_name,
                u_agent.full_name AS agent_name
            FROM service_report r
            JOIN service_req sr ON r.task_id = sr.task_id
            JOIN service_type st ON sr.service_type_id = st.service_type_id
            JOIN user u_agent ON r.agent_id = u_agent.user_id
            JOIN homeowner h ON sr.homeowner_id = h.user_id
            JOIN user u_cust ON h.user_id = u_cust.user_id
            WHERE h.company_id = :company_id
            ORDER BY r.created_at DESC
        ");
            $this->db->bind(':company_id', $companyId);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('get_reports_by_company failed: ' . $e->getMessage());
            return [];
        }
    }

    public function get_report_details($reportId)
    {
        $this->db->query("
        SELECT r.*, st.type_name AS service_type, u_agent.full_name AS agent_name
        FROM service_report r
        JOIN service_req sr ON r.task_id = sr.task_id
        JOIN service_type st ON sr.service_type_id = st.service_type_id
        JOIN user u_agent ON r.agent_id = u_agent.user_id
        WHERE r.report_id = :report_id
    ");
        $this->db->bind(':report_id', $reportId);
        return $this->db->single();
    }


    /**
     * Insert a delivery report (linked to an order, not a service_req task) and
     * mark the order as 'completed' — runs inside a single transaction.
     */
    public function insertDeliveryReportAndCompleteOrder(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $this->db->query("
                INSERT INTO service_report
                    (task_id, order_id, agent_id, actions_taken, replaced_parts, time_spent,
                     completion_date, technician_notes, final_status)
                VALUES
                    (NULL, :order_id, :agent_id, :actions_taken, :replaced_parts, :time_spent,
                     :completion_date, :technician_notes, :final_status)
            ");
            $this->db->bind(':order_id',         $data['order_id']);
            $this->db->bind(':agent_id',          $data['agent_id']);
            $this->db->bind(':actions_taken',     $data['actions_taken']);
            $this->db->bind(':replaced_parts',    $data['replaced_parts'] ?? '');
            $this->db->bind(':time_spent',        $data['time_spent']);
            $this->db->bind(':completion_date',   $data['completion_date']);
            $this->db->bind(':technician_notes',  $data['technician_notes'] ?? '');
            $this->db->bind(':final_status',      $data['final_status']);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }

            // Mark the order as completed
            $this->db->query("
                UPDATE orders SET status = 'completed'
                WHERE order_id = :order_id AND agent_id = :agent_id
            ");
            $this->db->bind(':order_id', $data['order_id']);
            $this->db->bind(':agent_id', $data['agent_id']);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('insertDeliveryReportAndCompleteOrder failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a delivery report already exists for a given order.
     */
    public function getDeliveryReportByOrderId(int $order_id): ?object
    {
        $this->db->query("SELECT * FROM service_report WHERE order_id = :order_id LIMIT 1");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single() ?: null;
    }
}
?>