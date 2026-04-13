<?php
class M_Help
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function add_complaint($data)
    {
        $this->db->query('INSERT INTO complaint (user_id, type, full_name, title, description) 
                          VALUES (:user_id, :type, :full_name, :title, :description)');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);

        return $this->db->execute();
    }

    public function get_all_complaints()
    {
        $this->db->query('SELECT 
                            c.complaint_id, 
                            u.full_name as customer, 
                            u.type as user_type,
                            c.title as title, 
                            c.description as notes, 
                            c.received_at as date,
                            c.status
                          FROM complaint c
                          JOIN user u ON c.user_id = u.user_id
                          ORDER BY c.received_at DESC');
        return $this->db->resultSet();
    }
}