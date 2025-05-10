<?php
session_start();
require 'paypal_config.php';

if (!isset($_SESSION['paypal_order_id'])) {
    die('Không tìm thấy mã đơn hàng PayPal!');
}

$paypalOrderId = $_SESSION['paypal_order_id'];

try {
    // Lấy access token từ PayPal
    $paypal = new PayPalConfig();
    $accessToken = $paypal->getAccessToken();

    // Gửi yêu cầu CAPTURE để xác nhận thanh toán
    $client = new GuzzleHttp\Client();
    $response = $client->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$paypalOrderId}/capture", [
        'headers' => [
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ],
    ]);

    $capture = json_decode($response->getBody(), true);

    // Kiểm tra trạng thái thanh toán
    if ($capture['status'] === 'COMPLETED') {
        // --- Cập nhật trạng thái đơn hàng ---
        require 'connection.php';
        $order_id = $_SESSION['order_id'];
        $user_id = $_SESSION['user_id'];
        $order_status = "Paid";
        $payment_date = date('Y-m-d H:i:s');
        $transaction_id = $paypalOrderId;

        // Update trạng thái đơn hàng
        $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
        $stmt->bind_param("si", $order_status, $order_id);
        $stmt->execute();

        // Lưu thông tin thanh toán vào bảng payments
        $stmt1 = $conn->prepare("INSERT INTO payments (order_id, user_id, transaction_id, payment_date) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param('iiss', $order_id, $user_id, $transaction_id, $payment_date);
        $stmt1->execute();

        echo "Thanh toán thành công! Số tiền: " . $capture['purchase_units'][0]['payments']['captures'][0]['amount']['value'] . " USD";
    } else {
        echo "Thanh toán chưa hoàn tất. Trạng thái hiện tại: " . $capture['status'];
    }
} catch (Exception $e) {
    die("Lỗi khi xác nhận thanh toán: " . $e->getMessage());
}
?>