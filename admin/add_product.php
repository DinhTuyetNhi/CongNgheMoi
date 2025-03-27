<?php
      
        
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

                <h2>Create Product</h2>
                
                <form action="create_product.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="productTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="productTitle" name="name" placeholder="Title" required>
                </div>
                <div class="mb-3">
                    <label for="productDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="productDescription" name="description" placeholder="Description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="productPrice" class="form-label">Price</label>
                    <input type="number" class="form-control" id="productPrice" name="price" placeholder="Price" required>
                </div>
                <div class="mb-3">
                    <label for="specialOffer" class="form-label">Special Offer/Sale</label>
                    <input type="number" class="form-control" id="specialOffer" name="offer" placeholder="Sale %" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="Sport Shoes" selected>Sport Shoes</option>
                        <option value="Sandals">Sandals</option>
                        <option value="High Heels">High Heels</option>
                        <option value="Boots">Boots</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" id="color" name="color" placeholder="Color">
                </div>
                <div class="mb-3">
                    <label for="image1" class="form-label">Image 1</label>
                    <input type="file" class="form-control" id="image1" name="image1">
                </div>
                <div class="mb-3">
                    <label for="image2" class="form-label">Image 2</label>
                    <input type="file" class="form-control" id="image2" name="image2">
                </div>
                <div class="mb-3">
                    <label for="image3" class="form-label">Image 3</label>
                    <input type="file" class="form-control" id="image3" name="image3">
                </div>
                <div class="mb-3">
                    <label for="image4" class="form-label">Image 4</label>
                    <input type="file" class="form-control" id="image4" name="image4">
                </div>
                <button type="submit" class="btn btn-primary" name="create_product">Create</button>
            </form>


            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
