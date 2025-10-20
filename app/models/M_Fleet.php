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
            $this->db->query('INSERT INTO user (email, password, type, full_name) VALUES (:email, :password, :type, :full_name)');
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':password', $userData['password']);
            $this->db->bind(':type', ROLE_HOMEOWNER);
            $this->db->bind(':full_name', $customerData['full_name']);
            $this->db->execute();

            // Get the inserted user ID
            $userId = $this->db->lastInsertId();

            // 2. Insert into `homeowner` table
            // Columns: user_id, company_id, full_name, address, contact, register_date, email
            $this->db->query('INSERT INTO homeowner (user_id, company_id, address, contact, register_date, nic, district) VALUES (:user_id, :company_id, :address, :contact, :register_date, :nic, :district)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', 1); 
            $this->db->bind(':address', $customerData['address']);
            $this->db->bind(':contact', $customerData['contact']);
            $this->db->bind(':register_date', date('Y-m-d'));
            $this->db->bind(':nic', $customerData['nic']);
            $this->db->bind(':district', $customerData['district']);
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