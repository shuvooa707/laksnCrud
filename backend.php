<?php

require_once("config.php");
require_once("database.php");
require_once("Models/Category.php");
require_once("Models/Product.php");



if (isset($_POST["operation"]) && $_POST["operation"] == "product.delete") 
{

    $pId = $_POST["id"];
    $db = new Database();

    echo json_encode($db->delete($pId, "products"));
    exit(0);
}

if (isset($_POST["operation"]) && $_POST["operation"] == "category.getchildren") 
{

    $cId = $_POST["id"];
    $category = new Category();

    echo json_encode($category->getChildren($cId));
    exit(0);
}



if (isset($_POST["operation"]) && $_POST["operation"] == "product.add") {

    // var_dump($_FILES["image"]);
    // exit(0);
    $category = new Product();

    // moving file 
    $uploads_dir = '/assets/images';
    
    $tmp_name = $_FILES["image"]["tmp_name"];
    $name = basename($_FILES["image"]["name"]);
    move_uploaded_file($tmp_name, __DIR__ . "$uploads_dir/$name");


    $r = $category->insert([
        "name" => $_POST["name"],
        "quantity" => $_POST["quantity"],
        "description" => $_POST["description"],
        "image" => $name,
        "category_id" => $_POST["category_id"],
    ]);
}

if (isset($_POST["operation"]) && $_POST["operation"] == "product.update") {

    $product = new Product();

    // moving file 
    $uploads_dir = '/assets/images';
    
    $tmp_name = $_FILES["image"]["tmp_name"];
    $name = basename($_FILES["image"]["name"]);
    move_uploaded_file($tmp_name, __DIR__ . "$uploads_dir/$name");


    $r = $product->update([
        "name" => $_POST["name"],
        "quantity" => $_POST["quantity"],
        "description" => $_POST["description"],
        "image" => $name,
        "category_id" => $_POST["category_id"],
        "id" => $_POST["id"]
    ]);
}

?>