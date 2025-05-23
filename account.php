<?php
include('server/connection.php');
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        header('location: login.php');
        exit;
    }
}

if (isset($_POST['update_info'])) {
    $new_name = $_POST['update_name'];
    $new_email = $_POST['update_email'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra email đã tồn tại chưa (trừ chính mình)
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE user_email=? AND user_id != ?");
    $check_stmt->bind_param("si", $new_email, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header('location: account.php?error=Email is already used by another account');
        exit;
    }

    // Cập nhật thông tin
    $stmt = $conn->prepare("UPDATE users SET user_name=?, user_email=? WHERE user_id=?");
    $stmt->bind_param("ssi", $new_name, $new_email, $user_id);

    if ($stmt->execute()) {
        // Cập nhật lại session
        $_SESSION['user_name'] = $new_name;
        $_SESSION['user_email'] = $new_email;
        header('location: account.php?message=Account information updated successfully');
        exit;
    } else {
        header('location: account.php?error=Failed to update account info');
        exit;
    }
}


if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    // Kiểm tra xem mật khẩu có khớp không
    if ($password !== $confirmPassword) {
        header('location: account.php?error=passwords dont match');
        exit;
    } 
    // Kiểm tra độ dài mật khẩu
    else if (strlen($password) < 6) {
        header('location: account.php?error=passwords must be at least 6 characters');
        exit;
    } 
    // Nếu không có lỗi
    else {
      // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
      $hashed_password = md5($password);
      $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
      $stmt->bind_param("ss", $hashed_password, $user_email);
  
      if ($stmt->execute()) {
          header('location: account.php?message=password has been updated successfully');
          exit;
      } else {
          header('location: account.php?error=could not update password');
          exit;
      }
  }
  
}

//get orders
  if(isset($_SESSION['logged_in'])){
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM orders  where user_id =?");
  $stmt->bind_param('i',$user_id);
  $stmt->execute();
  $orders = $stmt->get_result();

  }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trang thông tin cá nhân</title>
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
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
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
                            <a href="shop.php" class="nav-item nav-link">Sản Phẩm</a>
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

    <!--Account-->
    <section class="my-5 py-5">
        <div class="row container mx-auto">
          <?php if(isset($_GET['payment_message'])){?>
            <p class="mt-5 text-center" style="color:green"><?php echo $_GET['payment_message'];?></p>


          <?php   } ?>
            <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
            <p class="text-center" style = "color:green"><?php  if(isset($_GET['register_success'])){echo $_GET['register_success']; }?></p>
            <p class="text-center" style = "color:green"><?php  if(isset($_GET['login_success'])){echo $_GET['login_success']; }?></p>
                <h3 class="font-weight-bold">Thông tin tài khoản</h3>
                <hr class="mx-auto">
                <div class="account-info">
                    <p>Tên tài khoản:  <span> <?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name'];}?></span></p>
                    <p>Email: <span><?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email'];}?></span></p>
                    <p><a href="#orders" id="orders-btn">Đơn hàng của bạn</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Đăng xuất</a></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">

            <h3 class="font-weight-bold text-center">Update Account Info</h3>
                <hr class="mx-auto">
                <form method="POST" action="account.php">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="update_name" value="<?php echo $_SESSION['user_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="update_email" value="<?php echo $_SESSION['user_email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="update_info" value="Update Info">
                    </div>
                </form>


                <form id="account-form" method = "POST" action = "account.php">
                <p class="text-center" style = "color:red"><?php  if(isset($_GET['error'])){echo $_GET['error']; }?></p>
                <p class="text-center" style = "color:green"><?php  if(isset($_GET['message'])){echo $_GET['message'];} ?></p>
                    <h3 class="font-weight-bold text-center">Thay đổi mật khẩu</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label>Nhập mật khẩu mới</label>
                        <input type="password" class="form-control" id="account-password" name="password" placeholder="Nhập mật khẩu mới">
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Nhập lại mật khẩu">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="change_password" value="Thay đổi mật khẩu" id="change-pass-btn">
                    </div>
                </form>
            </div>
        </div>

    </section>

    <!--Orders-->
<section id="orders" class="orders container my-5 py-3">
  <div class="container mt-2">
    <h2 class="font-weight-bold text-center">Đơn hàng của bạn</h2>
    <hr class="mx-auto">
  </div>


<div class="container-fluid pt-5">
    <div class="row justify-content-center px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Chi phí đặt hàng</th>
                        <th>Trạng thái đơn hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Chi tiết đơn hàng</th>
                    </tr>
                </thead>
                <?php while($row = $orders->fetch_assoc()){ ?>
                  <tr>
                    <td>
                      <div class="product-infor">
                        
                        <div>
                          <p class="mt-3"><?php echo $row['order_id'];?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                    <span><?php echo $row['order_cost']; ?> đ</span>
                    </td>
                    <td>
                    <span><?php echo $row['order_status']; ?></span>
                    </td>
                    <td>
                      <span><?php echo $row['order_date']; ?></span>
                    </td>
                    <td>
                    <form method="POST" action="order_details.php">
                    <input type="hidden" value="<?php echo $row['order_status'];?>" name="order_status"/>
<input type="hidden" value="<?php echo $row['order_id'];?>" name="order_id"/>
<input class="btn" name="order_details_btn" type="submit" value="Xem chi tiết" style="color: #fff; background-color: #fb774b;" />

                  </form>

                    </td>
                  </tr>
                  <?php } ?>
            </table>
        </div>
    </div>
</div>
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
</body>
</html>