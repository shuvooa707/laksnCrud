<?php
require_once(__DIR__ . "\..\database.php");
class Model
{
    protected $db = null;

    public function __construct()
    {
        
    }

    public function all() 
    {
        if (!$this->table) return null;

        $sth = $this->db->conn->prepare("SELECT * FROM $this->table");
        $sth->execute();

        $result = $sth->fetchAll();

        return $result;
    }


    


    public function find($id)
    {
        if (!$this->table) return null;

        $sth = $this->db->conn->prepare("SELECT * FROM $this->table where id = ?");
        $sth->execute([$id]);

        $result = $sth->fetch();
        // var_dump($result["name"]);
        // exit(0);
        return $result;
    }
}