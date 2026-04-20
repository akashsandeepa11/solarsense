<?php
class M_Profile
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getSuperadminProfile($user_id)
    {
        $this->db->query("
        SELECT full_name, email FROM user WHERE user_id = :user_id
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single_assoc();
    }

    public function getHomeownerProfile($user_id)
    {
        $this->db->query("
        SELECT
            (SELECT full_name FROM user WHERE user_id = :user_id) AS full_name,
            (SELECT email FROM user WHERE user_id = :user_id) AS email,
            (SELECT contact FROM homeowner WHERE user_id = :user_id) AS phone_number,
            (SELECT address FROM homeowner WHERE user_id = :user_id) AS address,
            (SELECT district FROM homeowner WHERE user_id = :user_id) AS district,
            (SELECT capacity FROM solar_system WHERE user_id = :user_id) AS system_capacity, 
            (SELECT tilt FROM solar_system WHERE user_id = :user_id) AS system_tilt,
            (SELECT azimuth FROM solar_system WHERE user_id = :user_id) AS system_azimuth,
            (SELECT installation_date FROM solar_system WHERE user_id = :user_id) AS installation_date,
            (SELECT panel_brand FROM solar_system WHERE user_id = :user_id) AS panel_brand,
            (SELECT inverter_brand FROM solar_system WHERE user_id = :user_id) AS inverter_brand,
            (SELECT ceb_account FROM homeowner WHERE user_id = :user_id) AS ceb_account
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    public function updateHomeownerProfile($userId, $data)
    {
        // 1. Update core account details in user table
        $this->db->query("UPDATE user SET full_name = :full_name, email = :email WHERE user_id = :user_id");
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        // 2. Update homeowner-specific details
        $this->db->query("UPDATE homeowner SET contact = :contact, address = :address WHERE user_id = :user_id");
        $this->db->bind(':contact', $data['phone_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function getCompanyIdByUser($user_id)
    {
        $this->db->query("
        SELECT company_id 
        FROM installer_admin 
        WHERE user_id = :user_id
    ");

        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row->company_id : null;
    }

    public function getInstalleradminProfile($user_id, $company_id)
    {
        $this->db->query("
        SELECT
            (SELECT full_name FROM user WHERE user_id = :user_id) AS full_name,
            (SELECT email FROM user WHERE user_id = :user_id) AS email,
            (SELECT company_name FROM installer_company WHERE company_id = :company_id) AS company_name,
            (SELECT contact FROM installer_company WHERE company_id = :company_id) AS phone_number,
            (SELECT address FROM installer_company WHERE company_id = :company_id) AS address,
            (SELECT district FROM installer_company WHERE company_id = :company_id) AS district,
            (SELECT website FROM installer_company WHERE company_id = :company_id) AS website,
            (SELECT register_date FROM installer_company WHERE company_id = :company_id) AS register_date
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_id', $company_id);

        return $this->db->single_assoc();
    }

    public function updateInstalleradminProfile($companyId, $data)
    {
        $this->db->query("
        UPDATE installer_company 
        SET address = :address, 
            contact = :contact, 
            district = :district, 
            website = :website 
        WHERE company_id = :company_id
    ");

        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact', $data['contact']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':company_id', $companyId);

        return $this->db->execute();
    }

    public function getInstalleradminProfileByCompany($company_id)
    {
        $this->db->query("
        SELECT
            (SELECT full_name FROM user WHERE user_id = ia.user_id) AS full_name,
            (SELECT email FROM user WHERE user_id = ia.user_id) AS email,
            (SELECT contact FROM installer_company WHERE company_id = :company_id) AS phone_number,
            (SELECT address FROM installer_company WHERE company_id = :company_id) AS address,
            (SELECT district FROM installer_company WHERE company_id = :company_id) AS district,
            (SELECT website FROM installer_company WHERE company_id = :company_id) AS website,
            (SELECT register_date FROM installer_company WHERE company_id = :company_id) AS register_date
        ");
        $this->db->bind(':company_id', $company_id);
        return $this->db->single();
    }

    public function getServiceAgentProfile($user_id, $company_id)
    {
        $this->db->query("
        SELECT sa.user_id, sa.company_id, sa.address, sa.contact, sa.district, sa.register_date,
               sa.specialization, sa.status,
               u.full_name, u.email,
               ic.company_name
        FROM service_agent sa
        JOIN user u ON u.user_id = sa.user_id
        JOIN installer_company ic ON ic.company_id = sa.company_id
        WHERE sa.user_id = :user_id AND sa.company_id = :company_id 
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_id', $company_id);

        return $this->db->single_assoc();
    }

    public function updateServiceAgentProfile($userId, $userData)
    {
        $this->db->query('
        UPDATE service_agent 
        SET contact = :contact, address = :address, district = :district, specialization = :specialization, 
        status = :status
         WHERE user_id = :user_id');
        $this->db->bind(':contact', $userData['contactNumber']);
        $this->db->bind(':address', $userData['address']);
        $this->db->bind(':district', $userData['district']);
        $this->db->bind(':specialization', $userData['specialization']);
        $this->db->bind(':status', $userData['status']);
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        return true;
    }

    /**
     * Fetches full profile details for an Operation Manager
     */
    public function getOperationManagerProfile($user_id, $company_id)
    {
        $this->db->query("
        SELECT op.user_id, op.company_id, op.contact, op.address, op.district, 
               op.register_date, op.specialization, op.exp_level, op.team_size, 
               op.status, op.certifications, op.emergency_name, op.emergency_contact,
               u.full_name, u.email,
               ic.company_name
        FROM operation_manager op
        JOIN user u ON u.user_id = op.user_id
        JOIN installer_company ic ON ic.company_id = op.company_id
        WHERE op.user_id = :user_id AND op.company_id = :company_id 
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_id', $company_id);

        return $this->db->single_assoc();
    }

    /**
     * Updates Operation Manager specific fields
     */
    public function updateOperationManagerProfile($userId, $userData)
    {
        $this->db->query('
        UPDATE operation_manager 
        SET contact = :contact, 
            address = :address, 
            district = :district, 
            specialization = :specialization,
            exp_level = :exp_level,
            team_size = :team_size,
            status = :status,
            certifications = :certifications,
            emergency_name = :emergency_name,
            emergency_contact = :emergency_contact
        WHERE user_id = :user_id');

        $this->db->bind(':contact', $userData['contactNumber']);
        $this->db->bind(':address', $userData['physicalAddress']);
        $this->db->bind(':district', $userData['district']);
        $this->db->bind(':specialization', $userData['specialization']);
        $this->db->bind(':exp_level', $userData['exp_level']);
        $this->db->bind(':team_size', $userData['team_size']);
        $this->db->bind(':status', $userData['status']);
        $this->db->bind(':certifications', $userData['certifications']);
        $this->db->bind(':emergency_name', $userData['emergency_name']);
        $this->db->bind(':emergency_contact', $userData['emergency_contact']);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function getInventoryManagerProfile($user_id, $company_id)
    {
        $this->db->query("
        SELECT im.user_id, im.company_id, im.contact, im.address, im.district, 
               im.register_date, im.warehouse_location, im.warehouse_capacity, 
               im.exp_level, im.status, im.managed_categories, im.certifications, 
               im.emergency_name, im.emergency_contact,
               u.full_name, u.email,
               ic.company_name
        FROM inventory_manager im
        JOIN user u ON u.user_id = im.user_id
        JOIN installer_company ic ON ic.company_id = im.company_id
        WHERE im.user_id = :user_id AND im.company_id = :company_id 
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_id', $company_id);

        return $this->db->single_assoc();
    }

    /**
     * Updates Inventory Manager specific fields
     */
    public function updateInventoryManagerProfile($userId, $userData)
    {
        $this->db->query('
        UPDATE inventory_manager 
        SET contact = :contact, 
            address = :address, 
            district = :district, 
            warehouse_location = :warehouse_location,
            warehouse_capacity = :warehouse_capacity,
            exp_level = :exp_level,
            status = :status,
            managed_categories = :managed_categories,
            certifications = :certifications,
            emergency_name = :emergency_name,
            emergency_contact = :emergency_contact
        WHERE user_id = :user_id');

        $this->db->bind(':contact', $userData['contactNumber']);
        $this->db->bind(':address', $userData['physicalAddress']);
        $this->db->bind(':district', $userData['district']);
        $this->db->bind(':warehouse_location', $userData['warehouse_location']);
        $this->db->bind(':warehouse_capacity', $userData['warehouse_capacity']);
        $this->db->bind(':exp_level', $userData['exp_level']);
        $this->db->bind(':status', $userData['status']);
        $this->db->bind(':managed_categories', $userData['managed_categories']);
        $this->db->bind(':certifications', $userData['certifications']);
        $this->db->bind(':emergency_name', $userData['emergency_name']);
        $this->db->bind(':emergency_contact', $userData['emergency_contact']);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }
}