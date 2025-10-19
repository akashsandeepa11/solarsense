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

            // 1. Insert into `users` table
            $this->db->query('INSERT INTO users (email, password) VALUES (:email, :password)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', $userData['password']);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // 2. Insert into `homeowner` table with all solar system fields
            $this->db->query('INSERT INTO homeowner (user_id, full_name, email, address, contact, nic, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date, register_date) VALUES (:user_id, :full_name, :email, :address, :contact, :nic, :capacity, :tilt, :azimuth, :panel_brand, :inverter_brand, :installation_date, :register_date)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':full_name', $customerData['full_name']);
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':address', $customerData['address']);
            $this->db->bind(':contact', $customerData['contact']);
            $this->db->bind(':nic', $customerData['nic']);
            $this->db->bind(':capacity', $panelData['capacity']);
            $this->db->bind(':tilt', $panelData['tilt']);
            $this->db->bind(':azimuth', $panelData['azimuth']);
            $this->db->bind(':panel_brand', $panelData['panel_brand']);
            $this->db->bind(':inverter_brand', $panelData['inverter_brand']);
            $this->db->bind(':installation_date', $panelData['installation_date']);
            $this->db->bind(':register_date', date('Y-m-d'));
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
}