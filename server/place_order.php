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
    $payment_method = $_POST['payment'];
    $_SESSION['payment_method'] = $payment_method;

    if (isset($_SESSION['order_id'])) {
        // Cập nhật đơn hàng cũ
        $order_id = $_SESSION['order_id'];
        $stmt = $conn->prepare("UPDATE orders SET order_cost=?, user_phone=?, user_city=?, user_address=?, order_status=?, order_date=?, payment_method=? WHERE order_id=? AND user_id=?");
        $stmt->bind_param('issssssii', $order_cost, $phone, $city, $address, $order_status, $order_date, $payment_method, $order_id, $user_id);
        $stmt->execute();
        // Không thêm lại order_items, chỉ update thông tin giao hàng
    } else {
        // Đặt hàng mới từ giỏ hàng
        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date, payment_method) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isiissss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Lưu sản phẩm vào order_items như cũ
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
        unset($_SESSION['cart']);
    }

    // Chuyển hướng thanh toán như cũ
    $_SESSION['order_id'] = $order_id;
    if ($payment_method === 'qr') {
        header('Location: generate_qr.php');
        exit();
    } elseif ($payment_method === 'paypal') {
        header('Location: ../payment.php');
        exit();
    } elseif ($payment_method === 'cod') {
        header('Location: ../shipcod.php');
        exit();
    } else {
        header('Location: ../checkout.php?error=Phương thức thanh toán không hợp lệ!');
        exit();
    }
}
?>