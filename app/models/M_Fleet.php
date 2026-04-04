<?php
class M_Fleet{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    //add customer
    public function add_customer($userData, $customerData, $panelData) 
    {
        $plainPassword = substr(bin2hex(random_bytes(6)), 0, 10);
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Insert into `user` table
            $this->db->query('
            INSERT INTO user (email, password, type, full_name) 
            VALUES (:email, :password, :type, :full_name)
            ');

            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_HOMEOWNER);
            $this->db->bind(':full_name', $customerData['full_name']);
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

            // 2. Insert into `homeowner` table
            $sqlHomeowner = 'INSERT INTO homeowner (user_id, company_id, address, contact, register_date, nic, district, ceb_account) VALUES (:user_id, :company_id, :address, :contact, :register_date, :nic, :district, :ceb_account)';
            // Log the SQL and the values we'll bind to help diagnose missing field issues
            error_log('M_Fleet::add_customer homeowner SQL: ' . $sqlHomeowner);
            $binds = [
                ':user_id' => $userId,
                ':company_id' => $companyId,
                ':address' => $customerData['address'],
                ':contact' => $customerData['contact'],
                ':register_date' => date('Y-m-d'),
                ':nic' => $customerData['nic'],
                ':district' => $customerData['district'],
                ':ceb_account' => $customerData['ceb_account']
            ];
            error_log('M_Fleet::add_customer homeowner binds: ' . var_export($binds, true));

            $this->db->query($sqlHomeowner);
            foreach ($binds as $param => $val) {
                $this->db->bind($param, $val);
            }
            $this->db->execute();

            // 3. Insert into `solar_system` table
            // Columns: user_id, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date, module_type, array_type, losses_pct, dc_ac_ratio, inv_eff_pct
            $this->db->query('
            INSERT INTO solar_system (user_id, district, capacity, tilt, azimuth, panel_brand, inverter_brand, installation_date, module_type, array_type, losses_pct, dc_ac_ratio, inv_eff_pct) 
            VALUES (:user_id, :district, :capacity, :tilt, :azimuth, :panel_brand, :inverter_brand, :installation_date, :module_type, :array_type, :losses_pct, :dc_ac_ratio, :inv_eff_pct)
            ');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':district', $customerData['district']);
            $this->db->bind(':capacity', $panelData['system_capacity']);
            $this->db->bind(':tilt', $panelData['panel_tilt']);
            $this->db->bind(':azimuth', $panelData['panel_azimuth']);
            $this->db->bind(':panel_brand', $panelData['panel_brand']);
            $this->db->bind(':inverter_brand', $panelData['inverter_brand']);
            $this->db->bind(':installation_date', $panelData['installation_date']);
            $this->db->bind(':module_type', $panelData['module_type']);
            $this->db->bind(':array_type', $panelData['array_type']);
            $this->db->bind(':losses_pct', $panelData['losses_pct']);
            $this->db->bind(':dc_ac_ratio', $panelData['dc_ac_ratio']);
            $this->db->bind(':inv_eff_pct', $panelData['inv_eff_pct']);
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

    public function update_customer($userId, $userData, $customerData, $panelData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // 1. Update user table
            if (!empty($userData['password'])) {
                // Update with new password
                $this->db->query('UPDATE user SET email = :email, password = :password, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':password', $userData['password']);
                $this->db->bind(':full_name', $userData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            } else {
                // Update without password
                $this->db->query('UPDATE user SET email = :email, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':email', $userData['email']);
                $this->db->bind(':full_name', $userData['full_name']);
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

            // 2. Update homeowner table
            $this->db->query('
                UPDATE homeowner 
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

    public function delete_customer($userId)
{
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



    // public function delete_customer($userId) {
    //     try {
    //         // Start transaction
    //         $this->db->beginTransaction();

    //         // 1. Delete from service_agent table (child table first)
    //         $this->db->query('DELETE FROM customer WHERE user_id = :user_id');
    //         $this->db->bind(':user_id', $userId);
    //         $this->db->execute();

    //         // 2. Delete from user table
    //         $this->db->query('DELETE FROM user WHERE user_id = :user_id'); 
    //         $this->db->bind(':user_id', $userId);
    //         $this->db->execute();

    //         // Commit the transaction
    //         $this->db->commit();

    //         return true;

    //     } catch (Exception $e) {
    //         $this->db->rollBack();
    //         $errorMsg = 'Delete customer failed: ' . $e->getMessage();
    //         error_log($errorMsg);

    //         // Write to a file we can read easily
    //         if (!is_dir(dirname(__DIR__) . '/logs')) {
    //             mkdir(dirname(__DIR__) . '/logs', 0755, true);
    //         }
    //         file_put_contents(
    //             dirname(__DIR__) . '/logs/delete_customer_error.log', 
    //             date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
    //             FILE_APPEND
    //         );

    //         return false;
    //     }
    // }


}