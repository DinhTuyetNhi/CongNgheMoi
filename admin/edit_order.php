    <?php
       include('../server/connection.php');
        if(isset($_GET['order_id'])){
            $order_id = $_GET['order_id'];
            $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id=?");
            $stmt->bind_param("i",$order_id);
            $stmt->execute();

            $orders = $stmt->get_result();
        }elseif(isset($_POST['edit_order'])){
            $order_status = $_POST['order_status'];
            $order_id = $_POST['order_id'];
            $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
            $stmt->bind_param("si",$order_status, $order_id);

            if($stmt->execute()){
                header("location:dashboard.php?order_updated=Order has been updated successfully");
            }else{
                header("location:dashboard.php?order_failure=Error occured, try again");
            }
        }
        else{
            header("location:dashboard.php");
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

                <h2>Edit Order</h2>
                <form action="edit_order.php" method="POST">
                
                <?php foreach($orders as $r){?>
                <div class="mb-3">
                    <label for="orderId" class="form-label">Order Id</label>
                    <input type="text" class="form-control" id="orderId" name="orderId" readonly value="<?php echo $r['order_id'];?>">
                </div>
                <div class="mb-3">
                    <label for="orderPrice" class="form-label">Order Price</label>
                    <input type="text" class="form-control" id="orderPrice" name="orderPrice" readonly value="<?php echo $r['order_cost'];?>">
                </div>
                <input type="hidden" name="order_id" value="<?php echo $r['order_id'] ;?>"/>
                <div class="mb-3">
                    <label for="orderStatus" class="form-label">Order Status</label>
                    <select class="form-select" id="orderStatus" required name="order_status">
                        
                        <option value="Not Paid" <?php if($r['order_status'] == 'Not Paid'){echo "selected";} ?>>Not Paid</option>
                        <option value="Paid" <?php if($r['order_status'] == 'Paid'){echo "selected";} ?>>Paid</option>
                        <option value="Shipped" <?php if($r['order_status'] == 'Shipped'){echo "selected";} ?>>Shipped</option>
                        <option value="Delivered" <?php if($r['order_status'] == 'Delivered'){echo "selected";} ?>>Delivered</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="orderDate" class="form-label">Order Date</label>
                    <input type="text" class="form-control" id="orderDate" name="orderDate" readonly value="<?php echo $r['order_date'];?>">
                </div>
                <button type="submit" name="edit_order" class="btn btn-primary">Edit</button>
                <?php } ?>
            </form>


            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
