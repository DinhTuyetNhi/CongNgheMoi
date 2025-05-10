<?php
session_start();
include('connection.php');

if (isset($_POST['place_order'])) {
    // 1. Lấy thông tin từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $order_cost = $_SESSION['total'];
    $order_status = 'not paid';
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');
    $payment_method = $_POST['payment']; // Lấy phương thức thanh toán

    // 2. Lưu thông tin đơn hàng vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('isiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 3. Lưu thông tin sản phẩm trong giỏ hàng vào bảng `order_items`
    foreach ($_SESSION['cart'] as $key => $value) {
        $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $product_price = $product['product_price'];
        $product_quantity = $product['product_quantity'];

        $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) VALUES (?,?,?,?,?,?,?,?)");
        $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
        $stmt1->execute();
    }

    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);
    // 4. Xử lý chuyển hướng dựa trên phương thức thanh toán
    if ($payment_method === 'qr') {
        // Lưu thông tin đơn hàng vào session để hiển thị mã QR
        $_SESSION['order_id'] = $order_id;
        header('Location: generate_qr.php'); // Chuyển hướng đến trang mã QR
        exit();
    } elseif ($payment_method === 'paypal') {
        // Chuyển hướng đến trang thanh toán PayPal
        $_SESSION['order_id'] = $order_id;
        header('Location: ../payment.php');
        exit();
    } elseif ($payment_method === 'cod') {
        // Chuyển hướng đến trang cảm ơn
        $_SESSION['order_id'] = $order_id;
        header('Location: ../giaodiencod.html');
        exit();
    } else {
        // Phương thức thanh toán không hợp lệ
        header('Location: ../checkout.php?error=Phương thức thanh toán không hợp lệ!');
        exit();
    }
}
?>