<?php
class M_maintenance_history {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }


    public function get_agent_history() {

  
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
                WHERE sr.agent_id = :agent_id  AND sr.status = 'Completed' 
                ORDER BY sr.request_date DESC
            ");
            $this->db->bind(':agent_id', $_SESSION['user_id'] ?? 0);

            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('M_maintenance_history::get_agent_history failed: ' . $e->getMessage());
            return [];
        }
    }

   
  
}
?>
