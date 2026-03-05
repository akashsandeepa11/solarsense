<?php
class M_Service
{
    private $db;
    private $stmt;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function add_service_request($data)
    {
        try {
            $this->db->query('INSERT INTO service_task 
            (user_id, service_type, service_description, status, agent_id) 
            VALUES (:user_id, :service_type, :service_description, \'Pending\', NULL)');

            $this->db->bind(':user_id', $_SESSION['user_id']);  // No fallback to $_SESSION here unless needed
            $this->db->bind(':service_type', $data['service_type']);  // No (int) cast needed since DB is varchar
            $this->db->bind(':service_description', $data['service_description']);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Service Request Error: " . $e->getMessage());
            var_dump($e->getMessage());
            return false;
        }
    }

    public function get_service_history()
    {
        $this->db->query('SELECT * FROM service_task 
                      WHERE user_id = :user_id 
                      ORDER BY request_date DESC');

        $this->db->bind(':user_id', $_SESSION['user_id'] ?? 0);
        return $this->db->resultSet();
    }
}
?>