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
                account_no,
                meter_type,
                customer_name,
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
                raw_sms
            ) VALUES (
                :user_id,
                :created_at,
                :account_no,
                :meter_type,
                :customer_name,
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
                :raw_sms
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

        if (preg_match('/A\/C No:\s*(\d+)\s*\((.*?)\)/', $sms, $m)) {
            $data['account_no'] = $m[1];
            $data['meter_type'] = $m[2];
        } else {
            return false;
        }

        if (preg_match('/\)\s*\n([A-Z\.\s]+)/', $sms, $m)) {
            $data['customer_name'] = trim($m[1]);
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

}
?>