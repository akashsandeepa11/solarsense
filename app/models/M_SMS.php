<?php

class M_SMS
{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function parse_sms(){

    }

    public function upload_sms($data){
        $this->db->query("
            INSERT INTO ceb_sms_bills (
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
}
?>