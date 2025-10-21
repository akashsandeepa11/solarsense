<?php 

class M_inventory{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database;
    }



    //add inventory item
    public function add_item($data) {
    try {
        // Start transaction
        $this->db->beginTransaction();

        // Insert query (skip description for now)
        $this->db->query('
            INSERT INTO inventory (company_id,description, item_name, category, quantity, unit_price)
            VALUES (:company_id,:description, :item_name, :category, :quantity, :unit_price)
        ');

        // Bind values
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit_price', $data['unit_price']);

        // Execute
        $this->db->execute();

        // Commit
        $this->db->commit();

        return true;

    } catch (Exception $e) {
        $this->db->rollBack();
        error_log('Add inventory item failed: ' . $e->getMessage());
        return false;
    }

    }

    public function get_all_items() {
        $this->db->query("SELECT * FROM inventory WHERE company_id = :company_id");
        $this->db->bind(':company_id', 1);
        $items = $this->db->resultSet();

        

        return $items;
    }


    public function update_item($data) {
        $this->db->query('
            UPDATE inventory 
            SET item_name = :item_name, category = :category, quantity = :quantity, unit_price = :unit_price
            WHERE inventory_id = :inventory_id
        ');

        // Bind values
        $this->db->bind(':inventory_id', $data['inventory_id']);
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit_price', $data['unit_price']);

        // Execute
        return $this->db->execute();       


    }

    public function delete_item($inventory_id) {
        $this->db->query('DELETE FROM inventory WHERE inventory_id = :inventory_id');
        $this->db->bind(':inventory_id', $inventory_id);
        return $this->db->execute();
    }




}                    


?>