<?php
class m_auth{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    //add customer
    public function add_customer($userData, $customerData, $panelData) {
    try {
        // Start transaction
        $this->db->beginTransaction();

        // 1. Insert into `user` table
        $this->db->query('INSERT INTO user (username, password) VALUES (:email, :password)');
        $this->db->bind(':email', $userData['email']);
        $this->db->bind(':password', password_hash($userData['password'], PASSWORD_DEFAULT));
        $this->db->execute();

        // Get the inserted user ID
        $userId = $this->db->lastInsertId();

        // 2. Insert into `homeowner` table
        $this->db->query('INSERT INTO homeowner (user_id, full_name, address, contact, register_date, email) VALUES (:user_id, :fullName, :physicalAddress, :contactNumber, :register_date, :email)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':fullName', $customerData['full_name']);
        $this->db->bind(':physicalAddress', $customerData['address']);
        $this->db->bind(':contactNumber', $customerData['contact']);
        $this->db->bind(':register_date', date('Y-m-d')); // or $customerData['register_date']
        $this->db->bind(':email', $userData['email']);
        $this->db->execute();

        // 3. Insert into `solar_system` table
        $this->db->query('INSERT INTO solar_system (user_id, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date) VALUES (:user_id, :systemCapacity, :panelTilt, :azimuth, :panelBrand, :inverterBrand, :installationDate)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':systemCapacity', $panelData['capacity']);
        $this->db->bind(':panelTilt', $panelData['tilt']);
        $this->db->bind(':azimuth', $panelData['azimuth']);
        $this->db->bind(':panelBrand', $panelData['panel_brand']);
        $this->db->bind(':inverterBrand', $panelData['inverter_brand']);
        $this->db->bind(':installationDate', $panelData['installation_date']);
        $this->db->execute();

        // Commit the transaction
        $this->db->commit();

        return true;

    } catch (Exception $e) {
        $this->db->rollBack();
        error_log('Add customer failed: ' . $e->getMessage());
        return false;
    }
}


    //find the user
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM user WHERE email= :email');
    }

    //register installer
    public function register_installer($data){
        $this->db->query('INSERT INTO user(name, email, password) VALUES(:name, :email, :password)');
    }

}
?>