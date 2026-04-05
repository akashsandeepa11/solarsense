<?php
class M_Homeowner_Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
    public function getStats($user_id)
    {
        $this->db->query("
            SELECT 
                SUM(export_reading - prev_export_reading) AS total_export,
                SUM(import_reading - prev_import_reading) AS total_import,
                SUM((export_reading - prev_export_reading) - (import_reading - prev_import_reading)) AS total_generation
            FROM sms
            WHERE user_id = :user_id
        ");

        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
    public function getMonthlyGeneration($user_id, $year)
    {
        $this->db->query("
        SELECT 
            MONTH(reading_date) AS month,
            COALESCE(SUM((export_reading - prev_export_reading) - (import_reading - prev_import_reading)), 0) AS generation
        FROM sms
        WHERE user_id = :user_id
          AND YEAR(reading_date) = :year
        GROUP BY month
        ORDER BY month
    ");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }
}
?>