<?php

require_once("Model.php");

class Product extends Model
{
    protected $table = "products";

    public function __construct()
    {
        $this->db = new Database();
    }


    public function update($row)
    {
        if (!$row) return ["msg" => "failed"];

        try {
            $name = $row["name"];
            $id = $row["id"];
            $quantity = $row["quantity"] ?? 0;
            $description = $row["description"] ?? "";
            $image = $row["image"] ?? "product.png";
            $category_id = $row["category_id"];

            $sql = "UPDATE $this->table SET name = '$name', description='$description', image='$image', category_id='$category_id', quantity='$quantity' WHERE id='$id'";
            // echo $sql;
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute();
            echo json_encode(["msg" => "success", "id" => $id, "image" => $image]);
            exit(0);
        } catch (\Throwable $th) {
            var_dump($th);
            echo json_encode(["msg" => "failed"]);
            exit(0);
        }
    }


    public function insert($row)
    {
        if (!$row) return ["msg" => "failed"];

        try {
            $name = $row["name"];
            $quantity = $row["quantity"] ?? 0;
            $description = $row["description"] ?? "";
            $image = $row["image"] ?? "product.png";
            $category_id = $row["category_id"];

            $sql = "INSERT INTO $this->table (name, quantity, description, image,  category_id) VALUES (?,?,?,?,?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$name, $quantity, $description, $image, $category_id]);
            $id = $this-> db->conn->lastInsertId();
            echo json_encode(["msg" => "success", "id" => $id, "image" => $image]);
            exit(0);
        } catch (\Throwable $th) {
            echo json_encode(["msg" => "failed"]);
            exit(0);
        }
    }


    public function with($table)
    {
        if (!$table) return null;

        $sth = $this->db->conn->query("SELECT *, (select name from categories where categories.id = $this->table.category_id) AS category FROM $this->table");
    

        $result = $sth->fetchAll();

        // var_dump($result);
        // exit(0);
        return $result;
    }
}