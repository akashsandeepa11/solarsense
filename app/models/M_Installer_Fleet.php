<?php
class M_Installer_Fleet
{
    private $db;
    private $stmt;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get_verifications()
    {
        $this->db->query("SELECT company_id AS companyId, company_name, address, contact, email, request_date, status FROM installer_company ORDER BY request_date DESC");
        return $this->db->resultSet();
    }

    public function verify_company($companyId)
    {
        // 1. Generate PLAINTEXT password
        $plainPassword = substr(bin2hex(random_bytes(6)), 0, 10);

        try {
            $this->db->beginTransaction();

            // 2. Update company status
            $this->db->query("
            UPDATE installer_company 
            SET status = 'Verified' 
            WHERE company_id = :company_id
        ");
            $this->db->bind(':company_id', $companyId);
            $this->db->execute();

            // 3. Get company info
            $this->db->query("
            SELECT company_name, email 
            FROM installer_company 
            WHERE company_id = :company_id
        ");
            $this->db->bind(':company_id', $companyId);
            $company = $this->db->single();

            if (!$company) {
                throw new Exception("Company not found.");
            }

            // 4. Create user
            $this->db->query('
            INSERT INTO user (email, password, type, full_name)
            VALUES (:email, :password, :type, :full_name)
        ');
            $this->db->bind(':email', $company->email);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_INSTALLER_ADMIN);
            $this->db->bind(':full_name', $company->company_name);
            $this->db->execute();

            $userId = $this->db->lastInsertId();

            // 5. Link installer admin
            $this->db->query('
            INSERT INTO installer_admin (user_id, company_id, register_date)
            VALUES (:user_id, :company_id, :register_date)
        ');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', $companyId);
            $this->db->bind(':register_date', date('Y-m-d'));
            $this->db->execute();

            $this->db->commit();

            // 6. RETURN plaintext password + email
            return [
                'email' => $company->email,
                'password' => $plainPassword
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Company verification failed: ' . $e->getMessage());
            return false;
        }
    }

    //add installer admin account
    // public function add_installer_admin($userData, $installerAdminData) {
    //     try {
    //         var_dump($userData);
    //         var_dump($installerAdminData);

    //         $this->db->beginTransaction();


    // }


    

}