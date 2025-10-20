<?php
class M_Team{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    /**
     * Add a new service agent
     * Inserts into both user and service_agent tables within a transaction
     * 
     * @param array $userData - Contains: email, password
     * @param array $agentData - Contains: full_name, contact, nic, address, district, 
     *                          specialization, experience_years, availability, certifications, status
     * @return bool - true on success, false on failure
     */
    public function add_service_agent($userData, $agentData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Insert into `user` table
            // Columns: email, password, type
            $this->db->query('INSERT INTO user (email, password, type) VALUES (:email, :password, :type)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', $userData['password']);
            $this->db->bind(':type', ROLE_SERVICE_AGENT);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // 2. Insert into `service_agent` table
            // Columns: user_id, company_id, full_name, email, nic, address, contact, district, 
            //          specialization, experience_years, availability, certifications, status, register_date, created_date
            $this->db->query('
                INSERT INTO service_agent 
                (user_id, company_id, full_name, email, nic, address, contact, district, specialization, experience_years, availability, certifications, status, register_date, created_date) 
                VALUES 
                (:user_id, :company_id, :full_name, :email, :nic, :address, :contact, :district, :specialization, :experience_years, :availability, :certifications, :status, :register_date, :created_date)
            ');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', 1); // Default company_id - adjust as needed
            $this->db->bind(':full_name', $agentData['full_name']);
            $this->db->bind(':email', $userData['email']);
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
            $this->db->bind(':created_date', $agentData['created_date'] ?? date('Y-m-d H:i:s'));
            
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            return true;

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
    public function update_service_agent($userId, $agentId, $userData, $agentData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Update user table (only if password provided)
            if (!empty($userData['password'])) {
                $this->db->query('UPDATE user SET email = :email, password = :password WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':password', $userData['password']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            } else {
                // Update only email
                $this->db->query('UPDATE user SET email = :email WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

            // 2. Update service_agent table
            $this->db->query('
                UPDATE service_agent 
                SET full_name = :full_name,
                    email = :email,
                    nic = :nic,
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
            
            $this->db->bind(':full_name', $agentData['full_name']);
            $this->db->bind(':email', $userData['email']);
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
    public function delete_service_agent($userId, $agentId, $deleteUser = true) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Delete from service_agent table
            $this->db->query('DELETE FROM service_agent WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // 2. Delete from user table if requested
            if ($deleteUser) {
                $this->db->query('DELETE FROM user WHERE user_id = :user_id');
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

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

    /**
     * Get service agent by ID
     * 
     * @param int $userId - User ID
     * @return array|false - Agent data or false if not found
     */
    public function get_service_agent($userId) {
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
     * Get all service agents
     * 
     * @param array $filters - Optional filters (district, specialization, status, availability)
     * @return array - Array of agents or empty array
     */
    public function get_all_service_agents($filters = []) {
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

    /**
     * Check if email already exists
     * 
     * @param string $email - Email to check
     * @return bool - true if exists, false if not
     */
    public function email_exists($email) {
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
    public function nic_exists($nic, $excludeUserId = null) {
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
}
?>
