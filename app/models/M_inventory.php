<?php


class M_inventory{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database;
    }

    // Get all categories from item_category table
    public function get_categories() {
        $this->db->query("SELECT id, name FROM item_categories ORDER BY name");
        return $this->db->resultSet();
    }

    // Add new category
    public function add_category($name) {
        try {
            $this->db->query('INSERT INTO item_categories (name) VALUES (:name)');
            $this->db->bind(':name', $name);
            $this->db->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log('Add category failed: ' . $e->getMessage());
            return false;
        }
    }

    // Delete category by id
    public function delete_category($id) {
        try {
            $this->db->query('DELETE FROM item_categories WHERE id = :id');
            $this->db->bind(':id', (int)$id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Delete category failed: ' . $e->getMessage());
            return false;
        }
    }

    // Add inventory item
    public function add_item($data) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Insert query with category_id
            $this->db->query('
                INSERT INTO inventory (company_id, description, item_name, category_id, quantity, unit_price, buying_price, item_image)
                VALUES (:company_id, :description, :item_name, :category_id, :quantity, :unit_price, :buying_price, :item_image)
            ');

            // Bind values
            $this->db->bind(':company_id', $data['company_id']);
            $this->db->bind(':description', $data['description'] ?? '');
            $this->db->bind(':item_name', $data['item_name']);
            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':unit_price', $data['unit_price']);
            $this->db->bind(':buying_price', $data['buying_price'] ?? 0);
            $this->db->bind(':item_image', $data['item_image'] ?? null);

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
        $this->db->query("
            SELECT i.*, c.name as category_name 
            FROM inventory i 
            LEFT JOIN item_categories c ON i.category_id = c.id 
            WHERE i.company_id = :company_id
        ");
        $this->db->bind(':company_id', 1);
        return $this->db->resultSet();
    }

    public function get_total_items_count() {
        $this->db->query("SELECT COUNT(*) as total FROM inventory WHERE company_id = :company_id");
        $this->db->bind(':company_id', 1);
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function get_low_stock_items($threshold = 5) {
        $this->db->query("
            SELECT i.inventory_id as id, i.item_name as name, i.quantity, c.name as category_name
            FROM inventory i
            LEFT JOIN item_categories c ON i.category_id = c.id
            WHERE i.company_id = :company_id AND i.quantity <= :threshold
            ORDER BY i.quantity ASC
        ");
        $this->db->bind(':company_id', 1);
        $this->db->bind(':threshold', $threshold);
        return $this->db->resultSet();
    }

    public function get_total_stock_value() {
        $this->db->query("SELECT SUM(quantity * unit_price) as total_value FROM inventory WHERE company_id = :company_id");
        $this->db->bind(':company_id', 1);
        $row = $this->db->single();
        return $row->total_value ?? 0;
    }

    public function get_categories_count() {
        $this->db->query("SELECT COUNT(*) as total FROM item_categories");
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function get_stock_by_category() {
        $this->db->query("
            SELECT 
                COALESCE(c.name, 'Uncategorised') as category,
                SUM(i.quantity) as total_qty,
                SUM(i.quantity * i.unit_price) as total_value
            FROM inventory i
            LEFT JOIN item_categories c ON i.category_id = c.id
            WHERE i.company_id = :company_id
            GROUP BY c.id, c.name
            ORDER BY total_qty DESC
        ");
        $this->db->bind(':company_id', 1);
        return $this->db->resultSet();
    }

    public function get_recent_orders($limit = 5) {
        $this->db->query("
            SELECT order_id, user_id, total_amount, status, date
            FROM orders
            ORDER BY date DESC, order_id DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function get_total_orders_count() {
        $this->db->query("SELECT COUNT(*) as total FROM orders");
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function get_total_orders_amount() {
        $this->db->query("SELECT SUM(total_amount) as total FROM orders");
        $row = $this->db->single();
        return $row->total ?? 0;
    }


    public function update_item($data) {
        $this->db->query('
            UPDATE inventory 
            SET item_name = :item_name, category_id = :category_id, quantity = :quantity, 
                unit_price = :unit_price, buying_price = :buying_price
            WHERE inventory_id = :inventory_id
        ');

        $this->db->bind(':inventory_id', $data['inventory_id']);
        $this->db->bind(':item_name',    $data['item_name']);
        $this->db->bind(':category_id',  $data['category_id']);
        $this->db->bind(':quantity',     $data['quantity']);
        $this->db->bind(':unit_price',   $data['unit_price']);
        $this->db->bind(':buying_price', $data['buying_price'] ?? 0);

        return $this->db->execute();       
    }

    public function delete_item($inventory_id) {
        $this->db->query('DELETE FROM inventory WHERE inventory_id = :inventory_id');
        $this->db->bind(':inventory_id', $inventory_id);
        return $this->db->execute();
    }

    public function get_item_by_id($inventory_id) {
        $this->db->query('
            SELECT i.*, c.name as category_name 
            FROM inventory i 
            LEFT JOIN item_categories c ON i.category_id = c.id 
            WHERE i.inventory_id = :inventory_id
        ');
        $this->db->bind(':inventory_id', $inventory_id);
        $row = $this->db->single();
        
        if ($row) {
            return [
                'id'            => $row->inventory_id,
                'name'          => $row->item_name,
                'description'   => $row->description ?? '',
                'category_id'   => $row->category_id,
                'category_name' => $row->category_name,
                'quantity'      => $row->quantity,
                'unit_price'    => $row->unit_price,
                'buying_price'  => $row->buying_price ?? 0,
                'image'         => $row->item_image ?? null,
            ];
        }
        return null;
    }
}                    

?>