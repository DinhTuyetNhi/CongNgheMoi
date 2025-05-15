<?php
include('server/connection.php');

// Định dạng số điện thoại Việt Nam
function format_vietnam_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone); // chỉ lấy số
    if (strlen($phone) == 9) {
        $phone = '0' . $phone;
    }
    // Thêm dấu cách: 4-3-3
    return preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $phone);
}

if(isset($_POST['order_details_btn']) && isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];

    // Lấy trạng thái và thông tin đơn hàng từ bảng orders
    $stmt_order = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt_order->bind_param('i', $order_id);
    $stmt_order->execute();
    $order_info = $stmt_order->get_result()->fetch_assoc();
    $stmt_order->close();

    // Tính thời gian giao hàng dự kiến từ order_date
    $delivery_start = '';
    $delivery_end = '';
    if (!empty($order_info['order_date'])) {
        $date = new DateTime($order_info['order_date']);
        $delivery_start = clone $date;
        $delivery_start->modify('+3 days');
        $delivery_end = clone $date;
        $delivery_end->modify('+4 days');
    }

    // Lấy họ tên và email từ bảng users dựa vào user_id của đơn hàng
    $user_name = '';
    $user_email = '';
    if (isset($order_info['user_id'])) {
        $stmt_user = $conn->prepare("SELECT user_name, user_email FROM users WHERE user_id = ?");
        $stmt_user->bind_param('i', $order_info['user_id']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        if ($row_user = $result_user->fetch_assoc()) {
            $user_name = $row_user['user_name'];
            $user_email = $row_user['user_email'];
        }
        $stmt_user->close();
    }

    // Lấy chi tiết sản phẩm trong đơn hàng
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->bind_param('i',$order_id);
    $stmt->execute();
    $order_details = $stmt->get_result();
} else {
    header('location:account.php');
    exit();
}

$status = $order_info['order_status'];
switch ($status) {
    case 'not paid':
        $status_text = 'Chưa thanh toán';
        $status_class = 'status-unpaid';
        break;
    case 'on_hold':
        $status_text = 'Chờ xử lý';
        $status_class = 'status-hold';
        break;
    case 'shipped':
        $status_text = 'Đã giao hàng';
        $status_class = 'status-shipped';
        break;
    case 'delivered':
        $status_text = 'Đã nhận hàng';
        $status_class = 'status-delivered';
        break;
    default:
        $status_text = $status;
        $status_class = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trang xem chi tiết đơn hàng</title>
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
    
    <link rel="stylesheet" href="css/style3.css">
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


<!--Orders-->
<div class="container">
        <div class="header">
            <div class="logo">Liceria & Co</div>
            <div class="order-number">Đơn hàng #<?php echo htmlspecialchars($order_id); ?></div>
        </div>
        
        <div class="section">
            <div class="section-title">Trạng thái đơn hàng</div>
            <div class="status <?php echo $status_class; ?>">
                <?php echo $status_text; ?>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Thông tin đơn hàng</div>
            <div class="info-row">
                <div class="info-label">Ngày đặt hàng:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($order_info['order_date'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Họ tên người nhận:</div>
                <div class="info-value"><?php echo htmlspecialchars($user_name); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><?php echo htmlspecialchars(format_vietnam_phone($order_info['user_phone'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Địa chỉ giao hàng:</div>
                <div class="info-value"><?php echo htmlspecialchars($order_info['user_address'] . ', ' . $order_info['user_city']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($user_email); ?></div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Chi tiết sản phẩm</div>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($order_details->num_rows > 0): ?>
                        <?php while($item = $order_details->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="assets/imgs/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width:40px;vertical-align:middle;margin-right:8px;">
                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                </td>
                                <td class="price"><?php echo number_format($item['product_price'], 0, ',', '.'); ?>₫</td>
                                <td><?php echo htmlspecialchars($item['product_quantity']); ?></td>
                                <td class="price"><?php echo number_format($item['product_price'] * $item['product_quantity'], 0, ',', '.'); ?>₫</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Không có sản phẩm trong đơn hàng.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3">Tổng tiền</td>
                        <td class="price">
                            <?php
                                // Tính tổng tiền từ các sản phẩm trong đơn hàng
                                $total = 0;
                                // Lấy lại danh sách sản phẩm vì $order_details đã fetch hết ở trên
                                $stmt_total = $conn->prepare("SELECT product_price, product_quantity FROM order_items WHERE order_id = ?");
                                $stmt_total->bind_param('i', $order_id);
                                $stmt_total->execute();
                                $result_total = $stmt_total->get_result();
                                while ($row = $result_total->fetch_assoc()) {
                                    $total += $row['product_price'] * $row['product_quantity'] + 10;
                                }
                                $stmt_total->close();
                                echo number_format($total, 0, ',', '.') . '₫';
                            ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Thông tin vận chuyển</div>
            <div class="info-row">
                <div class="info-label">Đơn vị vận chuyển:</div>
                <div class="info-value">Giao hàng nhanh</div>
            </div>
            <div class="info-row">
                <div class="info-label">Thời gian dự kiến:</div>
                <div class="info-value">
                    <?php
                        if ($delivery_start && $delivery_end) {
                            echo $delivery_start->format('d/m/Y') . ' - ' . $delivery_end->format('d/m/Y');
                        } else {
                            echo 'Không xác định';
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="#" class="button" onclick="window.print(); return false;">In đơn hàng</a>
            <a href="account.php#orders" class="button" style="margin-left: 10px; background-color: #f0ad4e;">Quay lại</a>
        </div>
        
        <div class="footer">
            <p>Cảm ơn bạn đã mua hàng tại ShopVN!</p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email <strong>hotro@shopvn.com</strong> hoặc số điện thoại <strong>1900 1234</strong></p>
        </div>
    </div>


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
</html>

