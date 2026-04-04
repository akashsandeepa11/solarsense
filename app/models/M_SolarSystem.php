<?php

class M_SolarSystem
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get_by_user(int $userId)
    {
        $this->db->query("SELECT * FROM solar_system WHERE user_id = :user_id LIMIT 1");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }
}
?>
