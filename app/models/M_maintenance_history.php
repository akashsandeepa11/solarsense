<?php
class M_maintenance_history {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get full history for the logged-in agent from service_report.
     * Type is determined by null check:
     *   task_id IS NOT NULL  → service task
     *   order_id IS NOT NULL → installation job
     */
    public function get_agent_full_history(): array
    {
        try {
            $this->db->query("
                SELECT
                    srep.report_id,
                    srep.task_id,
                    srep.order_id,
                    srep.actions_taken,
                    srep.technician_notes,
                    srep.completion_date,
                    srep.final_status,
                    srep.time_spent,

                    -- Service task columns (NULL for installations)
                    st.type_name           AS task_title,
                    task_cust.full_name    AS task_customer,
                    h.address              AS task_address,
                    sreq.request_date      AS task_date,

                    -- Installation / order columns (NULL for service tasks)
                    o.order_id             AS linked_order_id,
                    o.total_amount,
                    o.date                 AS order_date,
                    order_cust.full_name   AS order_customer,
                    COUNT(oi.order_item_id) AS item_count

                FROM service_report srep

                -- service task joins
                LEFT JOIN service_req  sreq       ON srep.task_id          = sreq.task_id
                LEFT JOIN service_type st         ON sreq.service_type_id  = st.service_type_id
                LEFT JOIN homeowner    h          ON sreq.homeowner_id     = h.user_id
                LEFT JOIN user         task_cust  ON sreq.homeowner_id     = task_cust.user_id

                -- installation / order joins
                LEFT JOIN orders       o          ON srep.order_id         = o.order_id
                LEFT JOIN user         order_cust ON o.user_id             = order_cust.user_id
                LEFT JOIN order_item   oi         ON oi.order_id           = o.order_id

                WHERE srep.agent_id = :agent_id

                GROUP BY
                    srep.report_id, srep.task_id, srep.order_id,
                    srep.actions_taken, srep.technician_notes,
                    srep.completion_date, srep.final_status, srep.time_spent,
                    st.type_name, task_cust.full_name, h.address, sreq.request_date,
                    o.order_id, o.total_amount, o.date, order_cust.full_name

                ORDER BY srep.completion_date DESC
            ");
            $this->db->bind(':agent_id', (int)($_SESSION['user_id'] ?? 0));
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('M_maintenance_history::get_agent_full_history failed: ' . $e->getMessage());
            return [];
        }
    }
}
?>
