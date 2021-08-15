<?php
require_once("config.php");
require_once("database.php");
require_once("Models/Category.php");
require_once("Models/Product.php");

$db = new Database();

// inserting dummy data
// $db->insert([
//     "name" => "burger",
//     "quantity" => 1000,
//     "description" => "very tasty burger made with cheese and seseme sauce",
//     "image" => "product.png",
//     "category_id" => 1
// ], "products");



$db->delete(1, "products");


$categories = new Category();
$products = (new Product())->with("categories");
$categories->gchildrens();
// $categories->insert([
//     "name" => "Books"
// ]);
// foreach ($categories->all() as $product) {
//     echo $product['name'] . "<br>";
// }


// exit(0);


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">


    <link rel="stylesheet" href="./assets/css/index.css">

    <title>LaksnCrud</title>
</head>

<body>
    <?php include_once("navbar.php"); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-success px-5" data-toggle="modal" data-target="#addProductModal">+ Create New </button>
            </div>
            <div class="col-lg-12 my-2" id="table-container">
                <div class="overlay hide"></div>
                <table class="table table striped">
                    <thead class="bg-danger text-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Preview</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($products as $i => $product) {
                            echo "<tr data-id='" . $product['description'] . "'>
                                    <td>" . $i . "</td>
                                    <td>
                                        <a href='/product.php?id=" . $product['id'] . "'>
                                        " . $product['name'] . "
                                        </a>
                                    </td>
                                    <td>" . $product['quantity'] . "</td>
                                    <td>" . $product['description'] . "</td>
                                    <td>
                                       <img width='100px' src='assets/images/" . $product['image'] . "'>
                                    </td>
                                    <td>" . $product['category'] . "</td>
                                    <td>
                                        <button class='btn btn-info py-0 px-2 text-light m-1' onclick='editProduct(this.parentElement.parentElement, " . $product['id'] . ",)'>Edit</button>
                                        <button class='btn btn-danger py-0 px-2 text-light m-1' onclick='deleteProduct(this.parentElement.parentElement, " . $product['id'] . ")'>Delete</button>
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Create New Product Modal -->
    <div class="modal fade" id="addProductModal" data-selected-category="-1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Create product</h5>
                    <button type="button" class="close bg-danger text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="overlay hide"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <label for="name">Product Name : </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="name">Quantity : </label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="Description">Description : </label>
                                <textarea name="description" id="description" class="form-control" required>
                                </textarea>
                            </div>
                            <div class="form-group mt-2">
                                <label for="name">Image : </label>
                                <input oninput="previewImage(this)" type="file" name="image" id="image" class="form-control col-lg-6" required>
                                <img src="" alt="" class="hide my-3" height="200px">
                            </div>
                            <div class="form-group mt-2 category">
                                <label for="category">Category : </label>
                                <select onchange="loadChildren(this, this.value)" name="category" id="category" class="form-control" required>
                                    <option value="-1">Choose...</option>
                                    <?php
                                    foreach ($categories->gchildrens() as $category) {
                                        echo "
                                            <option value='" . $category['id'] . "'>" . $category['name'] . "</option>
                                            ";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-lg-12" onclick='add()'>Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Create New Product Modal -->




    <!-- Edit New Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Edit product</h5>
                    <button type="button" class="close bg-danger text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="overlay hide"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <label for="name">Product Name : </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="name">Quantity : </label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="Description">Description : </label>
                                <textarea name="description" id="description" class="form-control" required>
                                </textarea>
                            </div>
                            <div class="form-group mt-2">
                                <label for="name">Image : </label>
                                <input oninput="previewImageEditProductModal(this)" type="file" name="image" id="image" class="form-control col-lg-6" required>
                                <img src="" alt="" class="hide my-3" height="200px">
                            </div>
                            <div class="form-group mt-2">
                                <label for="category">Category : </label>
                                <select onchange="loadChildrenEditModal(this, this.value)" name="category" id="category" class="form-control" required>
                                    <option>Choose...</option>
                                    <?php
                                    foreach ($categories->gchildrens() as $category) {
                                        echo "
                                            <option value='" . $category['id'] . "'>" . $category['name'] . "</option>
                                            ";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-lg-12" onclick='updateProduct()'>Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit New Product Modal -->





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="./assets/js/index.js"></script>
</body>

</html>