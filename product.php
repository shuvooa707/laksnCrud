<?php
require_once("Models/Category.php");
require_once("Models/Product.php");

if (!isset($_GET["id"])) {
    header("Location:", "/");
}

$product = (new Product())->find($_GET["id"]);
$category = (new Category())->find($product["category_id"]);

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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <title>LaksnCrud</title>
</head>

<body>

    <?php include_once("navbar.php"); ?>

    <div class="container mt-5">
        <div class="row  d-flex justify-content-center">
            <div class="card" style="width: 20rem;">
                <div class="card-header bg-light d-flex justify-content-center">
                    <img style="max-height:250px; max-width:250px;" src="./assets/images/<?php echo htmlspecialchars($product['image']) ?>" class="" alt="...">
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Name : </strong>
                            <?php echo htmlspecialchars($product['name']) ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Quantity : </strong>
                            <?php echo htmlspecialchars($product['quantity']) ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Description : </strong>
                            <?php echo htmlspecialchars($product['description']) ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Category : </strong>
                            <?php echo htmlspecialchars($category['name']) ?>
                        </li>
                    </ul>
                </div>
                <!-- <div class="card-footer px-0 d-flex justify-content-between">
                    <div class="btn btn-success col-lg-5" onclick="editProduct(<?php echo $product['id'] ?>)">Edit</div>
                    <div class="btn btn-danger col-lg-5" onclick="deleteProduct(<?php echo $product['id'] ?>)">Delete</div>
                </div> -->
            </div>
        </div>
    </div>



    <!-- Edit New Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input type="file" name="image" id="image" class="form-control col-lg-6" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="category">Category : </label>
                                <select onchange="loadChildren(this, this.value)" name="category" id="category" class="form-control" required>
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
                    <button type="button" class="btn btn-danger col-lg-12 onclick='addProduct()'">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit New Product Modal -->

 

    <script src="./assets/js/product.js"></script>
</body>

</html>