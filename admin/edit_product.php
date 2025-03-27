<?php
        session_start();
        // Hiển thị dashboard nếu người dùng đã đăng nhập thành công
        if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
        exit();
        }

        include('../server/connection.php');
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];
            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
            $stmt->bind_param("i",$product_id);
            $stmt->execute();

            $products = $stmt->get_result(); //[]
        }
        elseif(isset($_POST['edit_btn'])){
            $product_id = $_POST['product_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $color = $_POST['color'];
            $offer = $_POST['offer'];
            $category = $_POST['category'];
            $stmt = $conn->prepare("UPDATE products SET product_name=?, product_description=?,
             product_price=?, product_special_offer=?, product_color=?, product_category=? WHERE product_id=?");
            $stmt->bind_param("ssssssi", $title, $description, $price, $offer, $color, $category, $product_id);

            if($stmt->execute()){
                header("location:products.php?edit_success_massage=Product has been updated successfully");
            }else{
                header("location:products.php?edit_failure_massage=Error occured, try again");
            }
        }
        else{
            header("location:products.php");
            exit();
        }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Nhi & Thanh Company</a>
        <a class="btn btn-outline-light" href="logout.php?logout=1">Sign out</a>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php">Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_product.php">Add New Product</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <h2>Edit Product</h2>
                <form id="edit_form" method="POST" action="edit_product.php" enctype="multipart/form-data">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="mb-2">

                <?php foreach($products as $product){ ?>

                    <input type="hidden" name="product_id" value="<?php echo $product['product_id'] ?>"/>
                    <label for="title" class="form-label"><b>Title</b></label>
                    <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name'];?>" name="title" required>
                </div>
                <div class="mb-2">
                    <label for="description" class="form-label"><b>Description</b></label>
                    <input type="text" class="form-control" id="product-desc" value="<?php echo $product['product_description'];?>" name="description" required>
                </div>
                <div class="mb-2">
                    <label for="price" class="form-label"><b>Price</b></label>
                    <input type="text" class="form-control" id="product-price"  value="<?php echo $product['product_price'];?>" name="price" required>
                </div>
                <div class="mb-2">
                    <label for="category" class="form-label"><b>Category</b></label>
                    <select class="form-select" id="category" required name="category">
                        <option value="Sport Shoes" selected>Sport Shoes</option>
                        <option value="Sandals">Sandals</option>
                        <option value="High Heels">High Heels</option>
                        <option value="Boots">Boots</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="color" class="form-label"><b>Color</b></label>
                    <input type="text" class="form-control" id="product-color" value="<?php echo $product['product_color'];?>" name="color" required>
                </div>
                <div class="mb-2">
                    <label for="special_offer" class="form-label"><b>Special Offer/Sale</b></label>
                    <input type="text" class="form-control" id="product-offer" value="<?php echo $product['product_special_offer'];?>" name="offer">
                </div>
                <button type="submit" class="btn btn-primary" name="edit_btn">Edit</button>

                <?php } ?>
            </form>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
