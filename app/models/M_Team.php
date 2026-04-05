<?php
class M_Team
{
    private $db;
    private $stmt;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Add a new service agent
     * Inserts into both user and service_agent tables within a transaction
     * 
     * @param array $userData - Contains: email, password
     * @param array $agentData - Contains: full_name, contact, nic, address, district, 
     *                          specialization, experience_years, availability, certifications, status
     * @return array|bool - On success returns an array with keys 'success','user_id','password','email'; on failure returns false
     */
    public function add_service_agent($userData, $agentData)
    {
        try {
            $plainPassword = substr(bin2hex(random_bytes(6)), 0, 10);
            // Start transaction
            $this->db->beginTransaction();

            // 1. Insert into `user` table
            // Columns: email, password, type
            $this->db->query('INSERT INTO user (email, password, type, full_name) VALUES (:email, :password, :type, :full_name)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_SERVICE_AGENT);
            $this->db->bind(':full_name', $agentData['full_name']);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // Get company id for the current installer admin
            $installerAdminId = $_SESSION['user_id'] ?? null;
            $this->db->query('SELECT company_id FROM installer_admin WHERE user_id = :user_id');
            $this->db->bind(':user_id', $installerAdminId);
            $companyRow = $this->db->single();
            $companyId = $companyRow->company_id ?? null;

            if (empty($installerAdminId)) {
                throw new Exception('Installer admin user_id is missing from session');
            }

            if (empty($companyId)) {
                // Log details for easier debugging
                $debug = 'InstallerAdmin lookup failed - user_id: ' . var_export($installerAdminId, true) . ', companyRow: ' . var_export($companyRow, true);
                error_log($debug);
                throw new Exception('Company ID not found for installer admin user_id: ' . var_export($installerAdminId, true));
            }

            // Verify the company actually exists in the installer_company table
            $this->db->query('SELECT company_id FROM installer_company WHERE company_id = :company_id');
            $this->db->bind(':company_id', $companyId);
            $companyExists = $this->db->single();
            if (empty($companyExists) || empty($companyExists->company_id)) {
                $debug = 'Installer company not found - company_id: ' . var_export($companyId, true) . ', installer_admin_row: ' . var_export($companyRow, true);
                error_log($debug);
                throw new Exception('Installer company not found for company_id: ' . var_export($companyId, true));
            }

            // 2. Insert into `service_agent` table
            // Columns: user_id, company_id, full_name, email, nic, address, contact, district, 
            //          specialization, experience_years, availability, certifications, status, register_date, created_date
            $this->db->query('
                INSERT INTO service_agent 
                (user_id, company_id, nic, address, contact, district, specialization, experience_years, availability, certifications, status, register_date) 
                VALUES 
                (:user_id, :company_id, :nic, :address, :contact, :district, :specialization, :experience_years, :availability, :certifications, :status, :register_date)
            ');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', $companyId);
            $this->db->bind(':nic', $agentData['nic']);
            $this->db->bind(':address', $agentData['address']);
            $this->db->bind(':contact', $agentData['contact_number']);
            $this->db->bind(':district', $agentData['district']);
            $this->db->bind(':specialization', $agentData['specialization']);
            $this->db->bind(':experience_years', $agentData['experience_years']);
            $this->db->bind(':availability', $agentData['availability']);
            $this->db->bind(':certifications', $agentData['certifications'] ?? NULL);
            $this->db->bind(':status', $agentData['status']);
            $this->db->bind(':register_date', date('Y-m-d'));

            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            // Return success with created user details (including plaintext password for emailing)
            return [
                'success' => true,
                'user_id' => $userId,
                'password' => $plainPassword,
                'email' => $userData['email'] ?? ''
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Add service agent failed: ' . $e->getMessage();
            error_log($errorMsg);

            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/add_service_agent_error.log',
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );

            return false;
        }
    }


    /**
     * Update an existing service agent
     * Updates both user and service_agent tables within a transaction
     * 
     * @param int $userId - User ID to update
     * @param int $agentId - Service Agent ID to update
     * @param array $userData - Contains: email, password (optional)
     * @param array $agentData - Contains agent details to update
     * @return bool - true on success, false on failure
     */
    public function update_service_agent($userId, $userData, $agentData)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Update user table (only if password provided)
            if (!empty($userData['password'])) {
                $this->db->query('UPDATE user SET email = :email, password = :password, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':password', $userData['password']);
                $this->db->bind(':full_name', $userData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            } else {
                // Update only email
                $this->db->query('UPDATE user SET email = :email, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':full_name', $userData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

            // 2. Update service_agent table
            $this->db->query('
                UPDATE service_agent 
                SET nic = :nic,
                    address = :address,
                    contact = :contact,
                    district = :district,
                    specialization = :specialization,
                    experience_years = :experience_years,
                    availability = :availability,
                    certifications = :certifications,
                    status = :status
                WHERE user_id = :user_id
            ');

            $this->db->bind(':nic', $agentData['nic']);
            $this->db->bind(':address', $agentData['address']);
            $this->db->bind(':contact', $agentData['contact_number']);
            $this->db->bind(':district', $agentData['district']);
            $this->db->bind(':specialization', $agentData['specialization']);
            $this->db->bind(':experience_years', $agentData['experience_years']);
            $this->db->bind(':availability', $agentData['availability']);
            $this->db->bind(':certifications', $agentData['certifications'] ?? NULL);
            $this->db->bind(':status', $agentData['status']);
            $this->db->bind(':user_id', $userId);

            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Update service agent failed: ' . $e->getMessage();
            error_log($errorMsg);

            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/update_service_agent_error.log',
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );

            return false;
        }
    }

    /**
     * Delete a service agent
     * Deletes from service_agent table and optionally from user table
     * 
     * @param int $userId - User ID to delete
     * @param int $agentId - Service Agent ID to delete
     * @param bool $deleteUser - Whether to also delete from user table (default: true)
     * @return bool - true on success, false on failure
     */
    public function delete_service_agent($userId)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Delete from service_agent table (child table first)
            $this->db->query('DELETE FROM service_agent WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // 2. Delete from user table
            $this->db->query('DELETE FROM user WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Delete service agent failed: ' . $e->getMessage();
            error_log($errorMsg);

            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/delete_service_agent_error.log',
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );

            return false;
        }
    }

    // Add this method to class M_Team in M_Team.php
    public function get_company_id_by_user($userId)
    {
        $this->db->query('SELECT company_id FROM installer_admin WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $row = $this->db->single();
        return $row->company_id ?? null;
    }

    /**
     * Get service agent by user ID (basic info - used for edit form population)
     * 
     * @param int $userId - User ID
     * @return object|false - Agent data or false if not found
     */
    public function get_service_agent($userId)
    {
        try {
            $this->db->query('
                SELECT u.user_id, u.email, sa.* 
                FROM service_agent sa
                JOIN user u ON sa.user_id = u.user_id
                WHERE sa.user_id = :user_id
            ');
            $this->db->bind(':user_id', $userId);

            return $this->db->single();

        } catch (Exception $e) {
            error_log('Get service agent failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get complete service agent data by user ID
     * Returns all data that was added during service agent creation
     * 
     * @param int $userId - User ID
     * @return object|false - Complete agent data object or false if not found
     */
    public function get_service_agent_complete($userId)
    {
        try {
            $this->db->query('
                SELECT 
                    u.user_id,
                    u.email,
                    u.full_name ,
                    u.type as user_type,
                    sa.nic,
                    sa.address,
                    sa.contact,
                    sa.district,
                    sa.specialization,
                    sa.experience_years,
                    sa.availability,
                    sa.certifications,
                    sa.status,
                    sa.register_date,
                    sa.company_id
                FROM service_agent sa
                JOIN user u ON sa.user_id = u.user_id
                WHERE sa.user_id = :user_id
            ');
            $this->db->bind(':user_id', $userId);

            $result = $this->db->single();

            if (!$result) {
                return false;
            }

            return $result;

        } catch (Exception $e) {
            error_log('Get service agent complete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all service agent data as array by user ID (includes all fields with aliases)
     * Useful for displaying complete agent information in various formats
     * 
     * @param int $userId - User ID
     * @return array - Comprehensive agent data with field name variations
     */
    public function get_service_agent_all_fields($userId)
    {
        try {
            $this->db->query('
                SELECT u.user_id, u.email, u.full_name as user_full_name, u.type, sa.* 
                FROM service_agent sa
                JOIN user u ON sa.user_id = u.user_id
                WHERE sa.user_id = :user_id
            ');
            $this->db->bind(':user_id', $userId);

            $result = $this->db->single();

            if (!$result) {
                return [];
            }

            // Build comprehensive array with all variations
            return [
                // User table fields
                'user_id' => $result->user_id,
                'email' => $result->email,
                'user_full_name' => $result->user_full_name,
                'user_type' => $result->type,

                // Service Agent table fields
                'full_name' => $result->full_name,
                'nic' => $result->nic,
                'address' => $result->address,
                'contact' => $result->contact,
                'contact_number' => $result->contact,
                'phone' => $result->contact,
                'district' => $result->district,
                'specialization' => $result->specialization,
                'experience_years' => $result->experience_years,
                'experienceYears' => $result->experience_years,
                'years_of_experience' => $result->experience_years,
                'availability' => $result->availability,
                'certifications' => $result->certifications,
                'status' => $result->status,
                'register_date' => $result->register_date,
                'created_date' => $result->created_date,
                'company_id' => $result->company_id,

                // Original object for backward compatibility
                'raw' => $result
            ];

        } catch (Exception $e) {
            error_log('Get service agent all fields failed: ' . $e->getMessage());
            return [];
        }
    }

    // create function for get_service_agents_by_company
    public function get_service_agents_by_company($companyId)
    {
        try {
            $this->db->query("
                SELECT
                    u.user_id AS id,
                    u.email,
                    u.full_name,
                    sa.contact,
                    sa.status AS agent_status,
                    COUNT(sr.task_id)                                                  AS assigned_tasks,
                    SUM(CASE WHEN sr.status = 'Completed' THEN 1 ELSE 0 END)          AS completed_tasks,
                    SUM(CASE WHEN sr.status = 'Pending'   THEN 1 ELSE 0 END)          AS pending_tasks
                FROM user u
                INNER JOIN service_agent sa ON u.user_id = sa.user_id
                LEFT JOIN service_req sr    ON sa.user_id = sr.agent_id
                WHERE sa.company_id = :company_id
                GROUP BY u.user_id, u.email, u.full_name, sa.contact, sa.status
            ");

            $this->db->bind(':company_id', $companyId);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('Get service agents by company failed: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Get all service agents
     * 
     * @param array $filters - Optional filters (district, specialization, status, availability)
     * @return array - Array of agents or empty array
     */
    public function get_all_service_agents($filters = [])
    {
        try {
            $query = '
                SELECT u.user_id, u.email, sa.* 
                FROM service_agent sa
                JOIN user u ON sa.user_id = u.user_id
                WHERE 1=1
            ';

            // Apply filters if provided
            if (!empty($filters['district'])) {
                $query .= ' AND sa.district = :district';
            }
            if (!empty($filters['specialization'])) {
                $query .= ' AND sa.specialization = :specialization';
            }
            if (!empty($filters['status'])) {
                $query .= ' AND sa.status = :status';
            }
            if (!empty($filters['availability'])) {
                $query .= ' AND sa.availability = :availability';
            }

            $query .= ' ORDER BY sa.created_date DESC';

            $this->db->query($query);

            // Bind filter parameters
            if (!empty($filters['district'])) {
                $this->db->bind(':district', $filters['district']);
            }
            if (!empty($filters['specialization'])) {
                $this->db->bind(':specialization', $filters['specialization']);
            }
            if (!empty($filters['status'])) {
                $this->db->bind(':status', $filters['status']);
            }
            if (!empty($filters['availability'])) {
                $this->db->bind(':availability', $filters['availability']);
            }

            return $this->db->resultSet();

        } catch (Exception $e) {
            error_log('Get all service agents failed: ' . $e->getMessage());
            return [];
        }
    }

    public function get_total_agents($companyId)
    {
        try {
            $this->db->query('
                SELECT COUNT(*) as total_agents
                FROM service_agent
                WHERE company_id = :company_id
            ');
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Get agent stats failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_active_agents($companyId)
    {
        try {
            $this->db->query('
                SELECT COUNT(*) as active_agents
                FROM service_agent
                WHERE company_id = :company_id AND status = "Active"
            ');
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Get active agents failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_total_tasks($companyId)
    {
        try {
            $this->db->query('
                SELECT COUNT(*) as total_tasks
                FROM service_req t
                JOIN service_agent sa ON t.agent_id = sa.user_id
                WHERE sa.company_id = :company_id
            ');
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Get total tasks failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_pending_tasks($companyId)
    {
        try {
            $this->db->query('
                SELECT COUNT(*) as pending_tasks
                FROM service_req t
                JOIN service_agent sa ON t.agent_id = sa.user_id
                WHERE sa.company_id = :company_id AND t.status = "Pending"
            ');
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Get pending tasks failed: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Check if email already exists
     * 
     * @param string $email - Email to check
     * @return bool - true if exists, false if not
     */
    public function email_exists($email)
    {
        try {
            $this->db->query('SELECT user_id FROM user WHERE email = :email');
            $this->db->bind(':email', $email);

            return $this->db->single() ? true : false;

        } catch (Exception $e) {
            error_log('Check email exists failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if NIC already exists
     * 
     * @param string $nic - NIC to check
     * @param int $excludeUserId - Optional: user ID to exclude from check (for updates)
     * @return bool - true if exists, false if not
     */
    public function nic_exists($nic, $excludeUserId = null)
    {
        try {
            if ($excludeUserId) {
                $this->db->query('SELECT user_id FROM service_agent WHERE nic = :nic AND user_id != :exclude_user_id');
                $this->db->bind(':exclude_user_id', $excludeUserId);
            } else {
                $this->db->query('SELECT user_id FROM service_agent WHERE nic = :nic');
            }

            $this->db->bind(':nic', $nic);

            return $this->db->single() ? true : false;

        } catch (Exception $e) {
            error_log('Check NIC exists failed: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Get service agents assigned to a specific customer via service_req
     */
    public function get_service_agents_by_customer($customerId)
    {
        try {
            $this->db->query("
                SELECT
                    u.user_id,
                    u.full_name,
                    u.email,
                    sa.contact,
                    sa.specialization,
                    sa.status                                                          AS agent_status,
                    COUNT(sr.task_id)                                                  AS total_tasks,
                    SUM(CASE WHEN sr.status = 'Completed' THEN 1 ELSE 0 END)          AS completed_tasks,
                    SUM(CASE WHEN sr.status = 'Pending'   THEN 1 ELSE 0 END)          AS pending_tasks
                FROM service_req sr
                JOIN user u              ON sr.agent_id  = u.user_id
                LEFT JOIN service_agent sa ON sa.user_id = u.user_id
                WHERE sr.homeowner_id = :customer_id
                GROUP BY u.user_id, u.full_name, u.email, sa.contact, sa.specialization, sa.status
            ");
            $this->db->bind(':customer_id', $customerId);
            return $this->db->resultSet() ?: [];
        } catch (Exception $e) {
            error_log('get_service_agents_by_customer failed: ' . $e->getMessage());
            return [];
        }
    }
}
?>