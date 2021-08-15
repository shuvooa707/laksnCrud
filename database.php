<?php
    require_once "config.php";
class Database {
    public $conn = null;

    public function __construct()
    {
        $this->conn = new PDO("mysql:host=" . constant("HOST") . ";dbname=" . constant("DATABASE"), constant("USERNAME"), constant("PASSWORD"));
    }

    public function all($table) 
    {
        if (!$table) return null;

        $sth = $this->conn->prepare("SELECT * FROM $table");
        $sth->execute();

        $result = $sth->fetchAll();

        return $result;
    }

    public function insert ($row, $table)
    {
        try {
            $name = $row["name"];
            $quantity = $row["quantity"] ?? 0;
            $description = $row["description"] ?? null;
            $image = $row["image"] ?? null;
            $category_id = $row["category_id"] ?? null;
            $sql = "INSERT INTO $table (name, quantity , description, image, category_id) VALUES (?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $quantity, $description, $image, $category_id]);
        } catch (\Throwable $th) {
            var_dump($th);
        }
        
    }


    public function delete($id = null, $table = null) 
    {
        if ( !$id || !$table ) return ["msg" => "failed"];

        try 
        {
            $sql = "DELETE FROM $table WHERE id=?";
            $stmt = $this->conn->prepare($sql);

            if ( $stmt->execute([$id]) ) 
            {
                return ["msg" => "success"];
            } else 
            {
                return ["msg" => "failed"];
            }
        } catch (\Throwable $th) 
        {
            return ["msg" => "failed"];
        }
    }
}