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
            (SELECT COUNT(company_id) FROM installer_company WHERE status = 'Verified') AS total_solar_companies,
            (SELECT COUNT(company_id) FROM installer_company WHERE status = 'Pending') AS pending_verifications,
            (SELECT COUNT(user_id) FROM user) AS total_platform_users
        ");
        return $this->db->single();

    }

    public function getCompanyGrowthByYear()
    {
        $this->db->query("
        SELECT 
            YEAR(register_date) AS year,
            COUNT(*) AS total
        FROM installer_company
        WHERE YEAR(register_date) BETWEEN 2022 AND 2026
        GROUP BY YEAR(register_date)
        ORDER BY year ASC
    ");

        return $this->db->resultSet();
    }

    public function getUserByType()
    {
        $this->db->query("
        SELECT 
            type AS user_type,
            COUNT(*) AS total
        FROM user
        GROUP BY user_type
        ");
        return $this->db->resultSet();
    }

    public function getCompanyByDistrict()
    {
        $this->db->query("
        SELECT 
            district,
            COUNT(*) AS total
        FROM installer_company
        WHERE status = 'Verified'
        GROUP BY district
        ORDER BY total DESC
        ");
        return $this->db->resultSet();
    }

    public function getVerificationRequests(){
        $this->db->query(" 
        SELECT 
            company_id,
            company_name,
            email, 
            district
        FROM installer_company
        WHERE status = 'Pending'
        ORDER BY register_date DESC
        LIMIT 3
        ");
        return $this->db->resultSet();
    }
    
    public function getActiveSystems(){
        $this->db->query("
        SELECT 
            COUNT(*) AS total_user
        FROM homeowner
        ");
        return $this->db->single();
    }
}
?>