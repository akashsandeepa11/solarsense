<?php
class M_Service
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get_service_types()
    {
          
    
        $this->db->query("SELECT service_type_id, type_name FROM service_type");
        $rows = $this->db->resultSet();

        // IMPORTANT: convert to dropdown format (id => name)
        $types = [];
        foreach ($rows as $row) {
            $types[$row->service_type_id] = $row->type_name;
        }

        return $types;
    
    }

    public function add_service_request($data)
    {
        $this->db->query("
            INSERT INTO service_req
            (service_type_id, service_description, request_date, homeowner_id, status, agent_id)
            VALUES
            (:service_type_id, :service_description, NOW(), :homeowner_id, 'Pending', NULL)
        ");

        $this->db->bind(':service_type_id', $data['service_type_id']);
        $this->db->bind(':service_description', $data['service_description']);
        $this->db->bind(':homeowner_id', $_SESSION['user_id']);

        return $this->db->execute();
    }

    public function cancel_service_request($task_id)
    {
        $this->db->query("
            DELETE FROM service_req
            WHERE task_id = :task_id
        ");

        $this->db->bind(':task_id', $task_id);

        return $this->db->execute();
    }

    public function get_service_history()
    {
        $this->db->query("
            SELECT 
                sr.task_id,
                sr.request_date,
                sr.status,
                st.type_name AS service_type,
                sr.service_description,
                u.full_name AS agent_name
            FROM service_req sr
            LEFT JOIN service_type st ON sr.service_type_id = st.service_type_id
            LEFT JOIN user u ON sr.agent_id = u.user_id
            WHERE sr.homeowner_id = :homeowner_id
            ORDER BY sr.request_date DESC
        ");

        $this->db->bind(':homeowner_id', $_SESSION['user_id']);

        return $this->db->resultSet();
    }


}
?>