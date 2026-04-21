<?php
class M_Manager{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    //add customer
    public function add_operation_manager($data) 
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

            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_OPERATION_MANAGER);
            $this->db->bind(':full_name', $data['fullName']);
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

            // 2. Insert into `operation_manager` table
            //Columns:user_id int PK, company_id int, contact int, nic int, address varchar(50) , district varchar(20) 
            //register_date datetime, , specialization varchar(20) , exp_level varchar(50) , team_size int 
            //status varchar(20) , certifications varchar(50) , emergency_name varchar(50) , emergency_contact int
            $this->db->query('
            INSERT INTO operation_manager (user_id, company_id, contact, nic, address, district, register_date, specialization, exp_level, team_size, status, certifications, emergency_name, emergency_contact) 
            VALUES (:user_id, :company_id, :contact, :nic, :address, :district, :register_date, :specialization, :exp_level, :team_size, :status, :certifications, :emergency_name, :emergency_contact)
            ');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', $companyId);
            $this->db->bind(':contact', $data['contactNumber']);
            $this->db->bind(':nic', $data['nic']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':district', $data['district']);
            $this->db->bind(':register_date', date('Y-m-d H:i:s'));
            $this->db->bind(':specialization', $data['specialization']);
            $this->db->bind(':exp_level', $data['experienceLevel']);
            $this->db->bind(':team_size', $data['teamSize']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':certifications', $data['certifications']);
            $this->db->bind(':emergency_name', $data['emergencyContactName']);
            $this->db->bind(':emergency_contact', $data['emergencyContactNumber']);

            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            // Return success with created user details (including plaintext password for emailing)
            return [
                'success' => true,
                'user_id' => $userId,
                'password' => $plainPassword,
                'email' => $data['email'] ?? ''
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Add operation manager failed: ' . $e->getMessage();
            error_log($errorMsg);
            
            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/add_operation_manager_error.log', 
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );
            
            return false;
        }
    }

    public function add_inventory_manager($data) 
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

            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', password_hash($plainPassword, PASSWORD_DEFAULT));
            $this->db->bind(':type', ROLE_INVENTORY_MANAGER);
            $this->db->bind(':full_name', $data['fullName']);
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

            // 2. Insert into `inventory_manager` table
            //Columns:user_id int PK, company_id int, contact int, nic int, address varchar(50) , district varchar(20) 
            //register_date datetime, , warehouse_location varchar(20) ,warehouse_capacity varchar(50) ,exp_level varchar(20) ,status varchar(20)
            //managed_categories varchar(50) ,certifications varchar(50) ,emergency_name varchar(50) ,emergency_contact int
            $this->db->query('
            INSERT INTO inventory_manager (user_id, company_id, contact, nic, address, district, register_date, warehouse_location, warehouse_capacity, exp_level, status, managed_categories, certifications, emergency_name, emergency_contact) 
            VALUES (:user_id, :company_id, :contact, :nic, :address, :district, :register_date, :warehouse_location, :warehouse_capacity, :exp_level, :status, :managed_categories, :certifications, :emergency_name, :emergency_contact)
            ');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':company_id', $companyId);
            $this->db->bind(':contact', $data['contactNumber']);
            $this->db->bind(':nic', $data['nic']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':district', $data['district']);
            $this->db->bind(':register_date', date('Y-m-d H:i:s'));
            $this->db->bind(':warehouse_location', $data['warehouseLocation']);
            $this->db->bind(':warehouse_capacity', $data['warehouseCapacity']);
            $this->db->bind(':exp_level', $data['experienceLevel']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':managed_categories', $data['managedCategories']);
            $this->db->bind(':certifications', $data['certifications']);
            $this->db->bind(':emergency_name', $data['emergencyContactName']);
            $this->db->bind(':emergency_contact', $data['emergencyContactNumber']);

            $this->db->execute();

            // Commit the transaction
            $this->db->commit();

            // Return success with created user details (including plaintext password for emailing)
            return [
                'success' => true,
                'user_id' => $userId,
                'password' => $plainPassword,
                'email' => $data['email'] ?? ''
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            $errorMsg = 'Add inventory manager failed: ' . $e->getMessage();
            error_log($errorMsg);
            
            // Write to a file we can read easily
            if (!is_dir(dirname(__DIR__) . '/logs')) {
                mkdir(dirname(__DIR__) . '/logs', 0755, true);
            }
            file_put_contents(
                dirname(__DIR__) . '/logs/add_inventory_manager_error.log', 
                date('Y-m-d H:i:s') . ' - ' . $errorMsg . "\n",
                FILE_APPEND
            );
            
            return false;
        }
    }

    /**
     * Update an existing operation manager
     */
    public function update_operation_manager($userId, $userData, $managerData)
    {
        try {
            $this->db->beginTransaction();

            // 1. Update user table
            if (!empty($userData['password'])) {
                $this->db->query('UPDATE user SET email = :email, password = :password, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':password', $userData['password']);
            } else {
                $this->db->query('UPDATE user SET email = :email, full_name = :full_name WHERE user_id = :user_id');
            }
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':full_name', $userData['full_name']);
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // 2. Update operation_manager table
            $this->db->query('
                UPDATE operation_manager 
                SET contact = :contact, nic = :nic, address = :address, district = :district,
                    specialization = :specialization, exp_level = :exp_level, team_size = :team_size,
                    status = :status, certifications = :certifications, 
                    emergency_name = :emergency_name, emergency_contact = :emergency_contact
                WHERE user_id = :user_id
            ');

            $this->db->bind(':contact', $managerData['contact']);
            $this->db->bind(':nic', $managerData['nic']);
            $this->db->bind(':address', $managerData['address']);
            $this->db->bind(':district', $managerData['district']);
            $this->db->bind(':specialization', $managerData['specialization']);
            $this->db->bind(':exp_level', $managerData['exp_level']);
            $this->db->bind(':team_size', $managerData['team_size']);
            $this->db->bind(':status', $managerData['status']);
            $this->db->bind(':certifications', $managerData['certifications']);
            $this->db->bind(':emergency_name', $managerData['emergency_name']);
            $this->db->bind(':emergency_contact', $managerData['emergency_contact']);
            $this->db->bind(':user_id', $userId);

            $this->db->execute();
            return $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Update operation manager failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing inventory manager
     */
    public function update_inventory_manager($userId, $userData, $managerData)
    {
        try {
            $this->db->beginTransaction();

            // 1. Update user table
            if (!empty($userData['password'])) {
                $this->db->query('UPDATE user SET email = :email, password = :password, full_name = :full_name WHERE user_id = :user_id');
                $this->db->bind(':password', $userData['password']);
            } else {
                $this->db->query('UPDATE user SET email = :email, full_name = :full_name WHERE user_id = :user_id');
            }
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':full_name', $userData['full_name']);
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // 2. Update inventory_manager table
            $this->db->query('
                UPDATE inventory_manager 
                SET contact = :contact, nic = :nic, address = :address, district = :district,
                    warehouse_location = :warehouse_location, warehouse_capacity = :warehouse_capacity,
                    exp_level = :exp_level, status = :status, managed_categories = :managed_categories,
                    certifications = :certifications, emergency_name = :emergency_name, 
                    emergency_contact = :emergency_contact
                WHERE user_id = :user_id
            ');

            $this->db->bind(':contact', $managerData['contact']);
            $this->db->bind(':nic', $managerData['nic']);
            $this->db->bind(':address', $managerData['address']);
            $this->db->bind(':district', $managerData['district']);
            $this->db->bind(':warehouse_location', $managerData['warehouse_location']);
            $this->db->bind(':warehouse_capacity', $managerData['warehouse_capacity']);
            $this->db->bind(':exp_level', $managerData['exp_level']);
            $this->db->bind(':status', $managerData['status']);
            $this->db->bind(':managed_categories', $managerData['managed_categories']);
            $this->db->bind(':certifications', $managerData['certifications']);
            $this->db->bind(':emergency_name', $managerData['emergency_name']);
            $this->db->bind(':emergency_contact', $managerData['emergency_contact']);
            $this->db->bind(':user_id', $userId);

            $this->db->execute();
            return $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Update inventory manager failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an operation manager
     */
    public function delete_operation_manager($userId) {
        try {
            $this->db->beginTransaction();
            // Delete from child table first
            $this->db->query('DELETE FROM operation_manager WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Delete from user table
            $this->db->query('DELETE FROM user WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            return $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Delete operation manager failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an inventory manager
     */
    public function delete_inventory_manager($userId) {
        try {
            $this->db->beginTransaction();
            // Delete from child table first
            $this->db->query('DELETE FROM inventory_manager WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Delete from user table
            $this->db->query('DELETE FROM user WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            return $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Delete inventory manager failed: ' . $e->getMessage());
            return false;
        }
    }

    
    /**
     * Fetch detailed operation manager info by user ID and company ID
     */
    public function get_operation_manager_details($managerId, $companyId) {
        try {
            $this->db->query('
                SELECT om.*, u.email, u.full_name 
                FROM operation_manager om
                JOIN user u ON om.user_id = u.user_id
                WHERE om.user_id = :manager_id AND om.company_id = :company_id
            ');
            $this->db->bind(':manager_id', $managerId);
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('get_operation_manager_details failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch detailed inventory manager info by user ID and company ID
     */
    public function get_inventory_manager_details($managerId, $companyId) {
        try {
            $this->db->query('
                SELECT im.*, u.email, u.full_name 
                FROM inventory_manager im
                JOIN user u ON im.user_id = u.user_id
                WHERE im.user_id = :manager_id AND im.company_id = :company_id
            ');
            $this->db->bind(':manager_id', $managerId);
            $this->db->bind(':company_id', $companyId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('get_inventory_manager_details failed: ' . $e->getMessage());
            return false;
        }
    }

    public function get_total_operation_managers() {
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_total_operation_managers failed: Installer admin user_id is missing from session');
            return 0;
        }

        try {
            $this->db->query('
                SELECT COUNT(om.user_id) AS total 
                FROM operation_manager om
                JOIN installer_admin ia ON om.company_id = ia.company_id
                WHERE ia.user_id = :user_id
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_total_operation_managers failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function get_active_service() {
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_active_service failed: Installer admin user_id is missing from session');
            return 0;
        }

        try {
            $this->db->query('
                SELECT COUNT(sr.service_request_id) AS total 
                FROM service_req sr
                JOIN installer_admin ia ON sr.company_id = ia.company_id
                WHERE ia.user_id = :user_id AND sr.status = "Active"
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_active_service_req failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function get_pending_service(){
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_pending_service failed: Installer admin user_id is missing from session');
            return 0;
        }

        try {
            $this->db->query('
                SELECT COUNT(sr.service_request_id) AS total 
                FROM service_req sr
                JOIN installer_admin ia ON sr.company_id = ia.company_id
                WHERE ia.user_id = :user_id AND sr.status = "Pending"
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_pending_service failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function get_completed_service(){
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_completed_service failed: Installer admin user_id is missing from session');
            return 0;
        }

        try {
            $this->db->query('
                SELECT COUNT(sr.service_request_id) AS total 
                FROM service_req sr
                JOIN installer_admin ia ON sr.company_id = ia.company_id
                WHERE ia.user_id = :user_id AND sr.status = "Completed"
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_completed_service failed: ' . $e->getMessage());
            return 0;
        }
    }

    public function get_operation_manager_by_company_id($companyId) {
        try {
            $this->db->query('
                SELECT om.*, u.email, u.full_name 
                FROM operation_manager om
                JOIN user u ON om.user_id = u.user_id
                WHERE om.company_id = :company_id
            ');
            $this->db->bind(':company_id', $companyId);

            $results = $this->db->resultSet();

            foreach($results as $row){
            // gender
                $nic = (string) ($row->nic ?? 0);
                $value = (int)substr($nic, 4, 3);
                

                if($value >= 500 )
                {
                    $row->gender = 'female';
                }
                else
                {
                    $row->gender = 'male';
                }

            };
            return $results;
        } catch (Exception $e) {
            error_log('get_operation_manager_by_company_id failed: ' . $e->getMessage());
            return [];
        }
    }

     public function get_inventory_manager_by_company_id($companyId) {
        try {
            $this->db->query('
                SELECT im.*, u.email, u.full_name 
                FROM inventory_manager im
                JOIN user u ON im.user_id = u.user_id
                WHERE im.company_id = :company_id
            ');
            $this->db->bind(':company_id', $companyId);

             $results = $this->db->resultSet();

            foreach($results as $row){
            // gender
                $nic = (string) ($row->nic ?? 0);
                $value = (int)substr($nic, 4, 3);
                

                if($value >= 500 )
                {
                    $row->gender = 'female';
                }
                else
                {
                    $row->gender = 'male';
                }

            };
            return $results;
        } catch (Exception $e) {
            error_log('get_inventory_manager_by_company_id failed: ' . $e->getMessage());
            return [];
        }
     }

     public function get_total_inventory_managers() {
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_total_inventory_managers failed: Installer admin user_id is missing from session');
            return 0;
        }

        try {
            $this->db->query('
                SELECT COUNT(im.user_id) AS total 
                FROM inventory_manager im
                JOIN installer_admin ia ON im.company_id = ia.company_id
                WHERE ia.user_id = :user_id
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_total_inventory_managers failed: ' . $e->getMessage());
            return 0;
        }
     }

     public function get_inventory(){
        //get count of inventory items for the company
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_inventory failed: Installer admin user_id is missing from session');
            return 0;
        }
        try {
            $this->db->query('
                SELECT COUNT(in.inventory_id) AS total 
                FROM inventory in
                JOIN installer_company ic ON in.company_id = ic.company_id
                JOIN installer_admin ia ON ic.company_id = ia.company_id
                WHERE ia.user_id = :user_id
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_inventory failed: ' . $e->getMessage());
            return 0;
        }
     }

     public function get_low_stock_items() {
        $installerAdminId = $_SESSION['user_id'] ?? null;
        if (empty($installerAdminId)) {
            error_log('get_low_stock_items failed: Installer admin user_id is missing from session');
            return 0;
        }
        try {
            $this->db->query('
                SELECT COUNT(in.item_name) AS total
                FROM inventory in
                JOIN installer_company ic ON in.company_id = ic.company_id
                JOIN installer_admin ia ON ic.company_id = ia.company_id
                WHERE ia.user_id = :user_id AND in.quantity < 10
            ');
            $this->db->bind(':user_id', $installerAdminId);
            $row = $this->db->single();
            return $row->total ?? 0;
        } catch (Exception $e) {
            error_log('get_low_stock_items failed: ' . $e->getMessage());
            return 0;
        }
     }
}
?>