<?php
class M_installer_registration{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    //add customer
    public function add_company($companyData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Insert into `user` table
            $this->db->query('INSERT INTO prospective_installer_company (company_name, address, contact, email) VALUES (:companyName, :physicalAddress, :contactNumber, :email)');
            $this->db->bind(':companyName', $companyData['companyName']);
            $this->db->bind(':physicalAddress', $companyData['physicalAddress']);
            $this->db->bind(':contactNumber', $companyData['contactNumber']);
            $this->db->bind(':email', $companyData['email']);

            $this->db->execute();


            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Add company failed: ' . $e->getMessage();
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