<?php 

class M_Test {

    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get_item(){

        $this->db->query('SELECT * FROM inventory');

        $this->db->execute();

        return $this->db->resultSet();

    }

}

?>