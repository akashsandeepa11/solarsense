<?php
class M_installer_registration{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    //receive verification request
    
    
    //add customer
    public function add_company($userData, $installerAdminData, $installerCompanyData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Insert into `user` table
            $this->db->query('INSERT INTO user (email, password, type, full_name) VALUES (:email, :password, :type, :full_name)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', $userData['password']);
            $this->db->bind(':type', ROLE_INSTALLER_ADMIN);
            $this->db->bind(':full_name', $installerAdminData['full_name']);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // 2. Insert into `installer_company` table
            // Columns: user_id, company_name, address, register_date, contact, email, verified_by
            $this->db->query('INSERT INTO installer_company (company_name, address, register_date, contact, email) VALUES (:companyName, :physicalAddress, :contactNumber, :email)');
            $this->db->bind(':companyName', $installerCompanyData['companyName']);
            $this->db->bind(':physicalAddress', $installerCompanyData['physicalAddress']);
            $this->db->bind(':contactNumber', $installerCompanyData['contactNumber']);
            $this->db->bind(':email', $installerCompanyData['email']);

            // 2. Insert into `installer_company` table
            // Columns: user_id, company_id, full_name, address, contact, register_date, email
            $this->db->query('INSERT INTO homeowner (user_id, company_id, address, contact, register_date, nic, district, ceb_account) VALUES (:user_id, :company_id, :address, :contact, :register_date, :nic, :district, :ceb_account)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', 1); 
            $this->db->bind(':address', $customerData['address']);
            $this->db->bind(':contact', $customerData['contact']);
            $this->db->bind(':register_date', date('Y-m-d'));
            $this->db->bind(':nic', $customerData['nic']);
            $this->db->bind(':district', $customerData['district']);
            $this->db->bind(':ceb_account', $customerData['ceb_account']);
            $this->db->execute();

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

    public function update_company($userId, $installerCompanyData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Update user table
            if (!empty($userData['password'])) {
                // Update with new password
                $this->db->query('UPDATE user SET company_name = :companyName, address = :physical address, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $installerCompanyData['email']);
                $this->db->bind(':password', $installerCompanyData['password']);
                $this->db->bind(':full_name', $installerCompanyData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            } else {
                // Update without password
                $this->db->query('UPDATE user SET email = :email, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $installerCompanyData['email']);
                $this->db->bind(':full_name', $installerCompanyData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

            // 2. Update homeowner table
            $this->db->query('
                UPDATE installer_company 
                SET address = :address,
                    contact = :contact,
                    nic = :nic,
                    district = :district,
                    ceb_account = :ceb_account
                WHERE user_id = :user_id
            ');
            
            $this->db->bind(':address', $customerData['address']);
            $this->db->bind(':contact', $customerData['contact']);
            $this->db->bind(':nic', $customerData['nic']);
            $this->db->bind(':district', $customerData['district']);
            $this->db->bind(':ceb_account', $customerData['ceb_account']);
            $this->db->bind(':user_id', $userId);

            $this->db->execute();

            // 3. Update solar_system table
            $this->db->query('
                UPDATE solar_system 
                SET capacity = :capacity,
                    tilt = :tilt,
                    azimuth = :azimuth,
                    panel_brand = :panel_brand,
                    inverter_brand = :inverter_brand,
                    installation_date = :installation_date
                WHERE user_id = :user_id
            ');
            
            $this->db->bind(':capacity', $panelData['system_capacity']);
            $this->db->bind(':tilt', $panelData['panel_tilt']);
            $this->db->bind(':azimuth', $panelData['panel_azimuth']);
            $this->db->bind(':panel_brand', $panelData['panel_brand']);
            $this->db->bind(':inverter_brand', $panelData['inverter_brand']);
            $this->db->bind(':installation_date', $panelData['installation_date']);
            $this->db->bind(':user_id', $userId);
            
            $this->db->execute();

            // Handle CEB account if it's stored separately (adjust based on your schema)
            // If ceb_account is in homeowner table, add it to the homeowner update above
            // If it's in solar_system table, add it to the solar_system update above
            // For now, assuming it might be in solar_system, so update if column exists
            if (!empty($panelData['ceb_account'])) {
                try {
                    $this->db->query('UPDATE solar_system SET ceb_account = :ceb_account WHERE user_id = :user_id');
                    $this->db->bind(':ceb_account', $panelData['ceb_account']);
                    $this->db->bind(':user_id', $userId);
                    $this->db->execute();
                } catch (Exception $e) {
                    // Column may not exist, continue without error
                }
            }

            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Update customer failed: ' . $e->getMessage();
            error_log($errorMsg);
            
            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/update_customer_error.log', 
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );
            
            return false;
        }
    }

    public function get_customer_details($userId) {
        try {
            $query = 'SELECT u.user_id, u.email, u.full_name, h.address, h.contact, h.nic, h.district, s.capacity as system_capacity, s.tilt as panel_tilt, s.azimuth as panel_azimuth, s.panel_brand, s.inverter_brand, s.installation_date, h.ceb_account FROM user u JOIN homeowner h ON u.user_id = h.user_id JOIN solar_system s ON u.user_id = s.user_id WHERE u.user_id = :user_id';
            
            $this->db->query($query);
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            $result = $this->db->single();
            
            // Log the result for debugging
            error_log('get_customer_details result for ID ' . $userId . ': ' . ($result ? 'Found' : 'Not Found'));
            
            return $result;
        } catch (Exception $e) {
            $errorMsg = 'Get customer details failed: ' . $e->getMessage();
            error_log($errorMsg);

            // Log into a readable file
            $logDir = dirname(__DIR__) . '/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            file_put_contents(
                $logDir . '/get_customer_details_error.log',
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );

            return false;
        }
    }

    public function get_customer_by_company($companyId) {
        try { 
            $this->db->query('SELECT 
                            u.user_id,
                            u.full_name,
                            u.email,
                            h.district,
                            s.capacity,
                            sm.date
                        FROM user u
                        JOIN homeowner h ON u.user_id = h.user_id
                        JOIN solar_system s ON u.user_id = s.user_id
                        LEFT JOIN (
                            SELECT user_id, MAX(date) as date
                            FROM sms
                            GROUP BY user_id
                        ) sm ON u.user_id = sm.user_id
                        WHERE h.company_id = :company_id');
            $this->db->bind(':company_id', $companyId);
            $results = $this->db->resultSet();

            // Format the results to match the expected structure
            $formattedResults = [];
            foreach ($results as $row) {
                $formattedResults[] = [
                    'id' => $row->user_id,
                    'name' => $row->full_name,
                    'location' => $row->district,
                    'size' => $row->capacity,
                    'health' => 'Healthy',  // Default dummy value
                    'performance' => '100',  // Default dummy value
                    'last_upload' => $row->date ?? '2025-08-20 09:45',  // Use actual date or default if NULL
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(str_replace(' ', '+', $row->full_name)) . '&background=00bcd4&color=fff'
                ];
            }
            
            return $formattedResults;
        } catch (Exception $e) {
            error_log('Get customer by company failed: ' . $e->getMessage());
            return false;
        }
    }

    public function delete_customer($userId){
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Delete from dependent tables first (if exist)
            $this->db->query('DELETE FROM homeowner WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            $this->db->query('DELETE FROM solar_system WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            $this->db->query('DELETE FROM sms WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // 2. Finally, delete from user table
            $this->db->query('DELETE FROM user WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            return true;

        } catch (Exception $e) {
            // Roll back if something goes wrong
            $this->db->rollBack();

            $errorMsg = 'Delete customer failed: ' . $e->getMessage();
            error_log($errorMsg);

            // Log into a readable file
            $logDir = dirname(__DIR__) . '/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            file_put_contents(
                $logDir . '/delete_customer_error.log',
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );

            return false;
        }
    }

}