<?php
class M_Installer_Fleet
{
    private $db;
    private $stmt;

    public function __construct()
    {
        $this->db = new Database();
    }

    //receive verification request
//     public function add_installer_verification($companyData)
//     {
//         try {
//             $this->db->beginTransaction();

//             $this->db->query('
//     INSERT INTO installer_company (
//         company_name,
//         address,
//         num_employees,
//         website,
//         district,
//         postal_code,
//         register_date,
//         contact,
//         email,
//         status,
//         request_date,
//         service_type,
//         years_experience,
//         complete_project,
//         service_areas
//     ) VALUES (
//         :company_name,
//         :address,
//         :num_employees,
//         :website,
//         :district,
//         :postal_code,
//         NOW(),
//         :contact,
//         :email,
//         :status,
//         NOW(),
//         :service_type,
//         :years_experience,
//         :complete_project,
//         :service_areas
//     )
// ');


//             $this->db->bind(':company_name', $companyData['company_name']);
//             $this->db->bind(':address', $companyData['address']);
//             $this->db->bind(':num_employees', $companyData['number_of_employees']);
//             $this->db->bind(':website', $companyData['website']);
//             $this->db->bind(':district', $companyData['district']);
//             $this->db->bind(':postal_code', $companyData['postal_code']);
//             $this->db->bind(':contact', $companyData['contact_number']);
//             $this->db->bind(':email', $companyData['email']);
//             $this->db->bind(':status', 'Pending');
//             $this->db->bind(':service_type', $companyData['service_type']);
//             $this->db->bind(':years_experience', $companyData['years_of_experience']);
//             $this->db->bind(':complete_project', $companyData['completed_projects']);
//             $this->db->bind(':service_areas', $companyData['service_areas']);

//             $this->db->execute();
//             $this->db->commit();

//             return true;

//         } catch (Exception $e) {
//             $this->db->rollBack();
//             error_log($e->getMessage());
//             return false;
//         }
//     }

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
            $company = $this->db->single_assoc();

            if (!$company) {
                throw new Exception("Company not found.");
            }

            // 4. Create user
            $this->db->query('
            INSERT INTO user (email, password, type, full_name)
            VALUES (:email, :password, :type, :full_name)
        ');
            $this->db->bind(':email', $company['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_INSTALLER_ADMIN);
            $this->db->bind(':full_name', $company['company_name']);
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
                'email' => $company['email'],
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

    public function update_company($userId, $installerCompanyData)
    {
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

            $this->db->execute();

            // Handle CEB account if it's stored separately (adjust based on your schema)
            // If ceb_account is in homeowner table, add it to the homeowner update above
            // If it's in solar_system table, add it to the solar_system update above
            // For now, assuming it might be in solar_system, so update if column exists
            if (!empty($panelData['ceb_account'])) {
                try {
                    $this->db->query('UPDATE solar_system SET ceb_account = :ceb_account WHERE user_id = :user_id');
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

    public function get_customer_details($userId)
    {
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

    public function get_customer_by_company($companyId)
    {
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

}