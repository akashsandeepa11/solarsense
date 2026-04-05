<?php

class M_SMS
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function upload_sms($data)
    {
        $this->db->query("
            INSERT INTO sms (
                user_id,
                created_at,
                balance_bf,
                reading_date,
                units_bf,
                export_reading,
                import_reading,
                prev_export_reading,
                prev_import_reading,
                consumption_units,
                monthly_bill,
                total_due,
                units_cf,
                raw_sms,
                expected_generation
            ) VALUES (
                :user_id,
                :created_at,
                :balance_bf,
                :reading_date,
                :units_bf,
                :export_reading,
                :import_reading,
                :prev_export_reading,
                :prev_import_reading,
                :consumption_units,
                :monthly_bill,
                :total_due,
                :units_cf,
                :raw_sms,
                :expected_generation
            )
        ");

        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        return $this->db->execute();
    }

    public function parse_sms($sms)
    {
        $data = [];

        if (!preg_match('/A\/C No:\s*(\d+)\s*\((.*?)\)/', $sms, $m)) {
            return false;
        }

        if (!preg_match('/\)\s*\n([A-Z\.\s]+)/', $sms, $m)) {
            return false;
        }

        if (preg_match('/B\/F:\s*Rs\.\s*([-\d,]+\.\d{2})/', $sms, $m)) {
            $data['balance_bf'] = floatval(str_replace(',', '', $m[1]));
        }

        if (preg_match('/Reading Date:\s*(\d{4}-\d{2}-\d{2})/', $sms, $m)) {
            $data['reading_date'] = $m[1];
        }

        if (preg_match('/B\/F Units:\s*(\d+)/', $sms, $m)) {
            $data['units_bf'] = (int) $m[1];
        }

        if (preg_match('/Readings:\s*(\d+)\(E\),\s*(\d+)\(I\)/', $sms, $m)) {
            $data['export_reading'] = (int) $m[1];
            $data['import_reading'] = (int) $m[2];
        }

        if (preg_match('/Prv\. Readings:\s*(\d+)\(E\),\s*(\d+)\(I\)/', $sms, $m)) {
            $data['prev_export_reading'] = (int) $m[1];
            $data['prev_import_reading'] = (int) $m[2];
        }

        if (preg_match('/Consumption:\s*(\d+)\s*Unit/', $sms, $m)) {
            $data['consumption_units'] = (int) $m[1];
        }

        if (preg_match('/Monthly Bill:\s*Rs\.\s*([-\d,]+\.\d{2})/', $sms, $m)) {
            $data['monthly_bill'] = floatval(str_replace(',', '', $m[1]));
        }

        if (preg_match('/Total Due:\s*Rs\.\s*([-\d,]+\.\d{2})/', $sms, $m)) {
            $data['total_due'] = floatval(str_replace(',', '', $m[1]));
        }

        if (preg_match('/C\/F Units:\s*(\d+)/', $sms, $m)) {
            $data['units_cf'] = (int) $m[1];
        }

        return $data;
    }

    public function sms_history()
    {
        $this->db->query("SELECT reading_date, export_reading, import_reading, consumption_units, monthly_bill FROM sms WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        return $this->db->resultSet();
    }

    public function check_duplicate_reading_date($user_id, $reading_date)
    {
        $this->db->query("SELECT COUNT(*) as cnt FROM sms WHERE user_id = :user_id AND reading_date = :reading_date");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':reading_date', $reading_date);
        $row = $this->db->single();
        return $row->cnt > 0;
    }

    public function get_chart_data(int $userId, int $limit = 12, ?int $year = null): array
    {
        $year = $year ?? (int) date('Y');

        $this->db->query("
            SELECT
                DATE_FORMAT(reading_date, '%b %Y')          AS label,
                reading_date,
                consumption_units,
                (export_reading - prev_export_reading)      AS grid_export,
                (import_reading - prev_import_reading)      AS grid_import,
                expected_generation,
                monthly_bill
            FROM sms
            WHERE user_id = :user_id
              AND YEAR(reading_date) = :year
            ORDER BY reading_date DESC
            LIMIT {$limit}
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':year', $year);
        $rows = $this->db->resultSet();

        return array_reverse((array) $rows);
    }

    public function get_available_years(int $userId): array
    {
        $this->db->query("
            SELECT DISTINCT YEAR(reading_date) AS yr
            FROM sms
            WHERE user_id = :user_id
            ORDER BY yr DESC
        ");
        $this->db->bind(':user_id', $userId);
        $rows = $this->db->resultSet();
        return array_column((array) $rows, 'yr');
    }
}
?>