<?php
class M_Superadmin_dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getStats()
    {
        $this->db->query("
        SELECT
            (SELECT COUNT(company_id) FROM installer_company WHERE status = 'verified') AS total_solar_companies,
            (SELECT COUNT(company_id) FROM installer_company WHERE status = 'pending') AS pending_verifications,
            (SELECT COUNT(user_id) FROM user) AS total_platform_users
        ");
    }
}
?>