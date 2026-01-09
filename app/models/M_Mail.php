<?php
class M_Mail {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    //Log the email attempt in the database
    public function logEmailSent($email) {
        $this->db->query("INSERT INTO email_logs (recipient, sent_at) VALUES (:email, NOW())");
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }
}