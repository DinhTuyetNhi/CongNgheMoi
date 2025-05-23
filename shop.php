<?php
include('server/connection.php');

      /*
      $stmt = $conn->prepare("SELECT * FROM products");
      $stmt->execute();
      $products = $stmt->get_result();

      if(isset($_POST['search'])){
          $category = $_POST['category'];
          $price = $_POST['price'];
          $stmt = $conn->prepare("SELECT * FROM products WHERE product_category=? AND product_price<=?");
          $stmt->bind_param("si",$category,$price);
          $stmt->execute();
          $products = $stmt->get_result(); //[]
          //return all products
        }
        else{*/

if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $keyword = "%" . $_GET['keyword'] . "%";

    // Tìm kiếm theo tên sản phẩm
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $products = $stmt->get_result();

    // Bỏ phân trang nếu đang tìm kiếm
    $total_no_of_pages = 1;
    $page_no = 1;
} else {
    // Xử lý phân trang như cũ
    if(isset($_GET['page_no']) && $_GET['page_no'] != ''){
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $stmt1 = $conn->prepare("SELECT count(*) AS total_records from products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    $total_records_per_page = 4;
    $offset = ($page_no - 1) * $total_records_per_page;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
}

      ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trang sản phẩm</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
</head>
<body>
    
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div id="nav" class="row bg-secondary py-2 px-xl-5 " >
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="">Câu hỏi thường gặp</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Giúp đỡ</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Hỗ trợ</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h4 class="m-0 display-5 font-weight-semi-bold" style="color: #d19c97;"><span class="text-primary font-weight-bold px-3 mr-1"><img src="assets/imgs/Logo.png" alt="" width="30%"></span>Liceria & Co</h4>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
            <form action="shop.php" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm sản phẩm"
                        value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text bg-transparent text-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <?php if (isset($_GET['keyword']) && $_GET['keyword'] != ''): ?>
                    <a href="shop.php" class="btn btn-outline-secondary ml-2">Xem tất cả</a>
                <?php endif; ?>
            </form>


            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                </a>
                <a href="cart.php" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Tất cả sản phẩm</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        <a href="shop.php" class="nav-item nav-link">Giày Thể Thao</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Sandal</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Boot</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Cao Gót</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link">Trang Chủ</a>
                            <a href="shop.php" class="nav-item nav-link active">Sản Phẩm</a>
                            <a href="introduce.php" class="nav-item nav-link">Tin Tức</a>
                            <a href="contact.php" class="nav-item nav-link">Cửa Hàng</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            <a href="login.php" class="nav-item nav-link">Đăng Nhập</a>
                            <a href="register.php" class="nav-item nav-link">Đăng Kí</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

      <!-- Page Header Start -->
      <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Sản phẩm của chúng tôi</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Sản phẩm</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->  

    <!--SHOP sport shoes-->
    <section id="featured" class="my-5 py-5">
        <div class="row mx-auto container">
          <?php
          if ($products && $products->num_rows > 0) {
            while($row = $products->fetch_assoc()){
          ?>
          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price">$<?php echo $row['product_price']; ?></h4>
            <a class="btn buy-btn" href="<?php echo "detail.php?product_id=". $row['product_id'];?>"> Mua Ngay</a>
          </div>
          <?php
            }
          } else {
            echo '<div class="col-12 text-center"><h4>Không tìm thấy sản phẩm nào phù hợp.</h4></div>';
          }
          ?>
        </div>
        <!-- Phân trang đưa ra ngoài hàng sản phẩm -->
        <?php if (!isset($_GET['keyword']) || $_GET['keyword'] == '') { ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-5">
                <?php for($i = 1; $i <= $total_no_of_pages; $i++) { ?>
                    <li class="page-item <?php if($i == $page_no) echo 'active'; ?>">
                    <a class="page-link" href="shop.php?page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                </ul>
            </nav>
            <?php } ?>
    </section>




    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold px-3 mr-1"><img src="assets/imgs/Logo.png" alt="" width="30%"></span></h1>
                </a>
                <p>Chúng tôi cung cấp những sản phẩm tốt nhất với mức giá phải chăng nhất.</p>

            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Liên kết nhanh</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang Chủ</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Sản Phẩm</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Tin tức</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ Hàng</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Thanh Toán</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Cửa Hàng</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Liên hệ với chúng tôi</h5>
                        <div class="d-flex flex-column justify-content-start">
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>12 Nguyen Van Bao, quan Go Vap, thanh pho Ho Chi Minh</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>091 234 5678</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Hệ thống cửa hàng</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 1</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 2</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 3</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 4</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 5</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Bản quyền thuộc về Liceria & Co Shop</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>