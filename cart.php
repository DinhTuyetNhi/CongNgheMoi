<?php
session_start();

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $product_price = $_POST['product_price'];
    $product_quantity = isset($_POST['product_quantity']) ? intval($_POST['product_quantity']) : 1; // Chắc chắn số lượng là một số nguyên

    // Tạo mảng sản phẩm để lưu trữ
    $product_array = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'product_image' => $product_image,
        'product_price' => $product_price,
        'product_quantity' => $product_quantity
    );

    // Kiểm tra xem giỏ hàng đã tồn tại chưa
    if(isset($_SESSION['cart'])){
        // Lấy mảng id của các sản phẩm trong giỏ hàng
        $products_array_ids = array_column($_SESSION['cart'], "product_id");

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if(!in_array($product_id, $products_array_ids)){
            // Nếu chưa, thêm sản phẩm mới vào giỏ hàng
            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            // Nếu sản phẩm đã tồn tại, tăng số lượng sản phẩm
            $_SESSION['cart'][$product_id]['product_quantity'] += $product_quantity;
        }
    
    } else {
        // Nếu giỏ hàng chưa tồn tại, khởi tạo giỏ hàng và thêm sản phẩm đầu tiên
        $_SESSION['cart'] = array();
        $_SESSION['cart'][$product_id] = $product_array;
    }
    
}

// Xóa sản phẩm khỏi giỏ hàng
else if(isset($_POST['remove_product'])){
    $product_id = $_POST['product_id'];
    if(isset($_SESSION['cart'][$product_id])){
        unset($_SESSION['cart'][$product_id]);
    }
}

// Chỉnh sửa số lượng sản phẩm trong giỏ hàng
else if(isset($_POST['edit_quantity'])){
    $product_id = $_POST['product_id'];
    $product_quantity = isset($_POST['product_quantity']) ? intval($_POST['product_quantity']) : 1; // Đảm bảo số lượng là một số nguyên

    // Kiểm tra xem sản phẩm có trong giỏ hàng không
    if(isset($_SESSION['cart'][$product_id])){
        // Cập nhật số lượng sản phẩm
        $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
    }
}


function calculateSubTotalCart(){
    $subtotal = 0;
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $key => $value){
        $product = $_SESSION['cart'][$key];

        $price = $product['product_price'];
        $quantity = $product['product_quantity'];

        $subtotal = $subtotal + ($price * $quantity);
    }   
    }
    $_SESSION['subtotal'] = $subtotal;
}

function calculateTotalCart(){
    $total = 0;
    $shipping = 0;

    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $shipping = 10; // Chỉ tính phí vận chuyển khi có sản phẩm trong giỏ hàng
        foreach($_SESSION['cart'] as $key => $value){
            $product = $_SESSION['cart'][$key];

            $price = $product['product_price'];
            $quantity = $product['product_quantity'];

            $total = $total + ($price * $quantity);
        }
    }

    // Cập nhật tổng tiền bao gồm phí vận chuyển
    $_SESSION['total'] = $total + $shipping;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Liceria & Co Shop</title>
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
                    <a class="text-dark" href="">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Help</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Support</a>
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
                        <input type="text" class="form-control" placeholder="Search for products">
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
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        <a href="shop.php" class="nav-item nav-link">Sport Shoes</a>
                        <a href="shop.php" class="nav-item nav-link">Sandals</a>
                        <a href="shop.php" class="nav-item nav-link">Boots</a>
                        <a href="shop.php" class="nav-item nav-link">High Heels</a>
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
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="shop.php" class="nav-item nav-link">Shop</a>
                            <a href="introduce.php" class="nav-item nav-link">Blog</a>
                            <a href="contact.php" class="nav-item nav-link">Contact Us</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            <a href="login.php" class="nav-item nav-link">Login</a>
                            <a href="register.php" class="nav-item nav-link">Register</a>
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
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shopping Cart</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shopping Cart</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
    <?php
    // Kiểm tra xem giỏ hàng có được khởi tạo và không trống
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Lặp qua từng sản phẩm trong giỏ hàng
        foreach($_SESSION['cart'] as $key => $value){ ?>
            <tr>
                <td class="align-middle">
                    <img src="assets/imgs/<?php echo $value['product_image']; ?>" alt="" style="width: 50px;">
                    <?php echo $value['product_name']; ?>
                </td>
                <td class="align-middle">$ <?php echo $value['product_price']; ?></td>
                <td class="align-middle">
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                        <div class="input-group quantity mx-auto" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-minus" type="submit" name="edit_quantity">
                                <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" name="product_quantity" class="form-control form-control-sm bg-secondary text-center" value="<?php echo $value['product_quantity']; ?>">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-plus" type="submit" name="edit_quantity">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </td>
                <td class="align-middle">$<?php echo $value['product_quantity'] * $value['product_price']?></td>
                <form method="POST" action="cart.php">
                    <td class="align-middle">
                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                        <button type="submit" class="btn btn-sm btn-primary" name="remove_product" value="remove">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </form>
            </tr>
        <?php }
    } else { ?>
        <!-- Thông báo khi giỏ hàng trống -->
        <tr>
            <td colspan="5" class="text-center">Your cart is empty!</td>
        </tr>
    <?php } ?>
</tbody>

                </table>
            </div>

            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">$ <?php
calculateSubTotalCart(); echo $_SESSION['subtotal'] ;?></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">$ <?php 
                // Hiển thị phí vận chuyển
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    echo 10; // Nếu có sản phẩm, phí vận chuyển là 10$
                } else {
                    echo 0; // Nếu giỏ hàng trống, phí vận chuyển là 0$
                }
                ?></h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">$ <?php //calculate total
calculateTotalCart(); echo $_SESSION['total'] ;?></h5>
                        </div>

                        <form method="POST" action="checkout.php">
                        <button class="btn btn-block btn-primary my-3 py-3" name="checkout" value="checkout" type="submit">Proceed To Checkout</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold px-3 mr-1"><img src="assets/imgs/Logo.png" alt="" width="30%" ></span></h1>
                </a>
                <p>We provide the best products for the most affordable prices.</p>

            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Quick Links</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Blog</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Shopping Cart</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Contact Us</h5>
                        <div class="d-flex flex-column justify-content-start">
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>12 Nguyen Van Bao, quan Go Vap, thanh pho Ho Chi Minh</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>091 234 5678</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Newsletter</h5>
                        <form action="">
                            <div class="form-group">
                                <input type="text" class="form-control border-0 py-4" placeholder="Your Name" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control border-0 py-4" placeholder="Your Email"
                                    required="required" />
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-3" type="submit">Subscribe Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Liceria & Co Shop</a>. All Rights Reserved.
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

</html>