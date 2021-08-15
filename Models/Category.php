<?php

require_once("Model.php");

class Category extends Model
{
    protected $table = "categories";

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getChildren($id) 
    {
        if (!$this->table) return null;

        $sth = $this->db->conn->prepare("SELECT * FROM $this->table WHERE parent_id = ?");
        $sth->execute([$id]);

        $result = $sth->fetchAll();

        return ["msg" => "success", "categories" => $result];
    }


    public function gchildrens()
    {
        // var_dump($value);
        // exit(1);
        if (!$this->table ) return null;

        $sth = $this->db->conn->prepare("SELECT * FROM $this->table WHERE parent_id = -1 ");
        $sth->execute();

        $result = $sth->fetchAll();

        return $result;
    }

    public function insert($row)
    {
        if (!$row) return ["msg" => "failed"];

        try {
            $name = $row["name"];
            $parent_id = $row["parent_id"] ?? null;
            $sql = "INSERT INTO $this->table (name, parent_id) VALUES (?,?)";
            $stmt = $this->db->conn->prepare($sql);
            if ($stmt->execute([$name, $parent_id])) {
                return json_encode(["msg" => "success"]);
            } else {
                return json_encode(["msg" => "failed"]);
            }
        } catch (\Throwable $th) {
            var_dump($th);
            exit(0);
        }
    }
}