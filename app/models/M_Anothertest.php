<?php 
class M_Anothertest{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get_item(){
        $this->db->query('SELECT * FROM orders');
        $this->db->execute();
        return $this->db->resultSet();
    }
}
?>