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
        $this->db->query("
        SELECT company_id AS companyId, company_name, address, contact, email, request_date, status 
        FROM installer_company 
        ORDER BY request_date DESC
        ");
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

    public function getAllCompaniesWithStats()
    {
        // Selecting specific columns: ID, Name, Email, Address, and Contact
        $this->db->query("
        SELECT 
            ic.company_id,
            ic.company_name,
            ic.email,
            ic.address,
            ic.contact,
            (SELECT COUNT(*) FROM homeowner h WHERE h.company_id = ic.company_id) as active_clients,
            (SELECT COUNT(*) FROM service_agent sa WHERE sa.company_id = ic.company_id AND sa.status = 'Available') as active_agents
        FROM installer_company ic
        WHERE ic.status = 'Verified'
        ORDER BY ic.company_id ASC
    ");
        return $this->db->resultSet();
    }

    public function add_verified_company($data)
    {
        $plainPassword = substr(bin2hex(random_bytes(6)), 0, 10);
        try {
            $this->db->beginTransaction();

            // 1. Insert into installer_company
            $this->db->query('INSERT INTO installer_company (company_name, address, num_employees, website, district, postal_code, register_date, contact, email, status, request_date, service_type, years_experience, complete_projects, service_areas) 
                          VALUES (:company_name, :address, :num_employees, :website, :district, :postal_code, NOW(), :contact, :email, "Verified", NOW(), :service_type, :years_experience, :complete_projects, :service_areas)');

            $this->db->bind(':company_name', $data['company_name']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':num_employees', $data['number_of_employees']);
            $this->db->bind(':website', $data['website']);
            $this->db->bind(':district', $data['district']);
            $this->db->bind(':postal_code', $data['postal_code']);
            $this->db->bind(':contact', $data['contact_number']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':service_type', $data['service_type']);
            $this->db->bind(':years_experience', $data['years_of_experience']);
            $this->db->bind(':complete_projects', $data['completed_projects']);
            $this->db->bind(':service_areas', $data['service_areas']);
            $this->db->execute();

            $companyId = $this->db->lastInsertId();

            // 2. Create User record for the Installer Admin
            $this->db->query('INSERT INTO user (email, password, type, full_name) VALUES (:email, :password, :type, :full_name)');
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_INSTALLER_ADMIN);
            $this->db->bind(':full_name', $data['company_name']);
            $this->db->execute();

            $userId = $this->db->lastInsertId();

            // 3. Link User to Company
            $this->db->query('INSERT INTO installer_admin (user_id, company_id, register_date) VALUES (:user_id, :company_id, NOW())');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', $companyId);
            $this->db->execute();

            $this->db->commit();
            return ['success' => true, 'password' => $plainPassword, 'user_id' => $userId];

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('add_verified_company failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_company_by_id($id)
    {
        $this->db->query('SELECT * FROM installer_company WHERE company_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Update existing company information
     */
    public function update_company($data)
    {
        $this->db->query('UPDATE installer_company SET 
                        company_name = :company_name, 
                        address = :address, 
                        num_employees = :num_employees, 
                        website = :website, 
                        district = :district, 
                        postal_code = :postal_code, 
                        contact = :contact, 
                        email = :email, 
                        service_type = :service_type, 
                        years_experience = :years_experience, 
                        complete_projects = :complete_projects, 
                        service_areas = :service_areas 
                      WHERE company_id = :company_id');

        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':num_employees', $data['number_of_employees']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':contact', $data['contact_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':service_type', $data['service_type']);
        $this->db->bind(':years_experience', $data['years_of_experience']);
        $this->db->bind(':complete_projects', $data['completed_projects']);
        $this->db->bind(':service_areas', $data['service_areas']);
        $this->db->bind(':company_id', $data['company_id']);

        return $this->db->execute();
    }

    /**
     * Remove a company from the system
     */
    public function delete_company($id)
    {
        try {
            $this->db->beginTransaction();

            // Deleting from installer_company
            $this->db->query('DELETE FROM installer_company WHERE company_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('delete_company failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_company_details($id)
    {
        $this->db->query("
        SELECT 
            ic.*, 
            u.email AS admin_email,
            u.full_name AS admin_name
        FROM installer_company ic
        LEFT JOIN installer_admin ia ON ic.company_id = ia.company_id
        LEFT JOIN user u ON ia.user_id = u.user_id
        WHERE ic.company_id = :id
    ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}