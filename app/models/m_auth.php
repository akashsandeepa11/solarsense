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
        $this->db->bind(':email', value: $email);

        $row = $this->db->single();
        $hashed_password = $row->password;
        
        if(password_verify($password, $hashed_password)){
            return $row;
        }else{
            return false;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
    
        redirect('Users/login');
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

}
?>