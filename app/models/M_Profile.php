<?php
class M_Profile
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getSuperadminProfile($user_id){
        $this->db->query("
        SELECT full_name, email FROM user WHERE user_id = :user_id
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
}
?>