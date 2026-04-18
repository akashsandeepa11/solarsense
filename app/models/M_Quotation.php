<?php
class M_Quotation
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get counts for the Hero section statistics
     */
    public function get_landing_stats()
    {
        // Count total homeowners
        $this->db->query("SELECT COUNT(*) as count FROM homeowner");
        $homeowners = $this->db->single()->count;

        // Count verified installer companies
        $this->db->query("SELECT COUNT(*) as count FROM installer_company WHERE status = 'Verified'");
        $installers = $this->db->single()->count;

        return [
            'homeowner_count' => $homeowners,
            'installer_count' => $installers
        ];
    }

    /**
     * Get list of verified installers for Step 1
     */
    public function get_verified_installers()
    {
        $this->db->query("SELECT * FROM installer_company WHERE status = 'Verified' ORDER BY register_date DESC");
        return $this->db->resultSet();
    }

    /**
     * Save the quotation request to the database
     */
    public function save_quotation($data)
    {
        $this->db->query('INSERT INTO quotation (company_id, customer_name, customer_phone, customer_email, customer_address, bill_range, roof_type, property_type, existing_system) 
                          VALUES (:company_id, :name, :phone, :email, :address, :bill, :roof, :property, :existing)');

        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['customer_name']);
        $this->db->bind(':phone', $data['customer_phone']);
        $this->db->bind(':email', $data['customer_email']);
        $this->db->bind(':address', $data['customer_address']);
        $this->db->bind(':bill', $data['bill_amount']);
        $this->db->bind(':roof', $data['roof_type']);
        $this->db->bind(':property', $data['property_type']);
        $this->db->bind(':existing', $data['existing_system']);

        return $this->db->execute();
    }

    public function get_quotations_by_company($companyId)
    {
        $this->db->query("
        SELECT 
            quotation_id AS id,
            customer_name AS customer,
            customer_email AS email,
            customer_phone AS contact,
            customer_address AS address,
            bill_range,
            roof_type,
            property_type,
            existing_system,
            status,
            created_at AS date
        FROM quotation 
        WHERE company_id = :company_id
        ORDER BY created_at DESC
    ");
        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }

    public function update_status($id, $status)
    {
        $this->db->query("UPDATE quotation SET status = :status WHERE quotation_id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete_quotation($id)
    {
        $this->db->query("DELETE FROM quotation WHERE quotation_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}