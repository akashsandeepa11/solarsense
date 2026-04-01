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
        return $this->db->single();
    }

    public function getHomeownerProfile($user_id)
    {
        $this->db->query("
        SELECT
            (SELECT full_name FROM user WHERE user_id = :user_id) AS full_name,
            (SELECT email FROM user WHERE user_id = :user_id) AS email,
            (SELECT contact FROM homeowner WHERE user_id = :user_id ) AS phone_number,
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
            (SELECT contact FROM installer_company WHERE company_id = :company_id) AS phone_number,
            (SELECT address FROM installer_company WHERE company_id = :company_id) AS address,
            (SELECT district FROM installer_company WHERE company_id = :company_id) AS district,
            (SELECT website FROM installer_company WHERE company_id = :company_id) AS website,
            (SELECT register_date FROM installer_company WHERE company_id = :company_id) AS register_date
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_id', $company_id);

        return $this->db->single();
    }
}
?>