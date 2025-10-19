<?php
class M_Auth{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    // login user
    public function login($email, $password){
        $this->db->query("SELECT * FROM user WHERE email = :email");
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($row){
            return $row;
        }else{
            return false;
        }
    }

    //find the user
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        
        if($row){
            return true;
        }else{
            return false;
        }
    }
    

    //register installer
    public function register_installer($data){
        $this->db->query('INSERT INTO user (email, password) VALUES (:email, :password)');
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        return $this->db->execute();
    }

}
?>