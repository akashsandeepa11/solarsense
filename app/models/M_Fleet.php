<?php
class M_Fleet{
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
            $this->db->query('INSERT INTO user (email, password, type) VALUES (:email, :password, :type)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', $userData['password']);
            $this->db->bind(':type', ROLE_HOMEOWNER);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // 2. Insert into `homeowner` table
            // Columns: user_id, company_id, full_name, address, contact, register_date, email
            $this->db->query('INSERT INTO homeowner (user_id, company_id, full_name, address, contact, register_date, email) VALUES (:user_id, :company_id, :full_name, :address, :contact, :register_date, :email)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', 1); // Default company_id - adjust as needed
            $this->db->bind(':full_name', $customerData['full_name']);
            $this->db->bind(':address', $customerData['address']);
            $this->db->bind(':contact', $customerData['contact']);
            $this->db->bind(':register_date', date('Y-m-d'));
            $this->db->bind(':email', $userData['email']);
            $this->db->execute();

            // 3. Insert into `solar_system` table
            // Columns: user_id, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date
            $this->db->query('INSERT INTO solar_system (user_id, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date) VALUES (:user_id, :capacity, :tilt, :azimuth, :panel_brand, :inverter_brand, :installation_date)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':capacity', $panelData['system_capacity']);
            $this->db->bind(':tilt', $panelData['panel_tilt']);
            $this->db->bind(':azimuth', $panelData['panel_azimuth']);
            $this->db->bind(':panel_brand', $panelData['panel_brand']);
            $this->db->bind(':inverter_brand', $panelData['inverter_brand']);
            $this->db->bind(':installation_date', $panelData['installation_date']);
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Add customer failed: ' . $e->getMessage();
            error_log($errorMsg);
            
            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/add_customer_error.log', 
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );
            
            return false;
        }
    }
}