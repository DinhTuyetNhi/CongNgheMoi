<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('location: login.php');
  exit();
}

// Hiển thị dashboard nếu người dùng đã đăng nhập thành công
?>

<?php
    include('../server/connection.php');
    //1. determine page number
    if(isset($_GET['page_no']) && $_GET['page_no'] != ''){
        //if user has already entered page then page number is the one that they selected
        $page_no = $_GET['page_no'];
      }
      else{
        //if user just entered the page then default page is 1
        $page_no = 1;
      }

      //2. return number of products 
      $stmt1 = $conn->prepare("SELECT count(*) AS total_records from orders");
      $stmt1->execute();
      $stmt1->bind_result($total_records);
      $stmt1->store_result();
      $stmt1->fetch();

      //3.produt per page
      $total_records_per_page = 5;
      
      $offset = ($page_no-1) * $total_records_per_page;

      $previous_page = $page_no - 1 ;
      $next_page = $page_no + 1 ;

      $adjacents = "2";
      $total_no_of_pages = ceil($total_records/$total_records_per_page);

      //4. get all products
      $stmt2 = $conn->prepare("SELECT * FROM orders LIMIT $offset,$total_records_per_page");
      $stmt2->execute();
      $orders = $stmt2->get_result();
    
      /*
    $stmt = $conn->prepare("SELECT * FROM orders");
    $stmt->execute();
    $orders = $stmt->get_result();
    */
  

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

                <h2>Orders</h2>

                <?php if(isset($_GET['order_updated'])){?>
                <p class="text-center" style="color:green;"><?php echo $_GET['order_updated']; ?></p>
                <?php } ?>

                <?php if(isset($_GET['order_failure'])){?>
                <p class="text-center" style="color:red;"><?php echo $_GET['order_failure']; ?></p>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Status</th>
                                <th>User ID</th>
                                <th>Order Date</th>
                                <th>User Phone</th>
                                <th>User Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order){ ?>
                            <tr>
                                <td><?php echo $order['order_id']?></td>
                                <td><?php echo $order['order_status']?></td>
                                <td><?php echo $order['user_id']?></td>
                                <td><?php echo $order['order_date']?></td>
                                <td><?php echo $order['user_phone']?></td>
                                <td><?php echo $order['user_address']?></td>
                                <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id'];?>">Edit</a></td>
                                <td><a class="btn btn-danger">Delete</a></td>
                            </tr>
                           <?php } ?>
                        </tbody>

                    </table>
                    
                       
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-5 mx-auto">
                            <li class="page-item <?php if($page_no<=1){echo 'disabled';} ?>">
                            <a class="page-link" href="<?php if($page_no<=1){echo '#';}else{echo '?page_no='.($page_no-1);
                            } ?>">Previous</a>
                            </li>

                            <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                            <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>
                            <?php if($page_no >=3){?>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="<?php echo"?page_no=".$page_no;?>"><?php echo $page_no;?></a></li>
                            <?php } ?>
                            <li class="page-item <?php if($page_no>= $total_no_of_pages){echo 'disabled';}?>">
                            <a class="page-link" href="<?php if($page_no>= $total_no_of_pages){echo '#';}else{echo '?page_no='.($page_no +1);
                            } ?>">Next</a></li>
                        </ul>
                    </nav>



                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
