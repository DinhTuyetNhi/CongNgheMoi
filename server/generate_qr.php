<?php
// filepath: c:\xampp\htdocs\project3\project\server\generate_qr.php

session_start();
require 'paypal_config.php';

if (!isset($_SESSION['order_id']) || !isset($_SESSION['total'])) {
    header('Location: ../checkout.php?error=Vui lòng đặt hàng trước!');
    exit();
}

$order_id = $_SESSION['order_id'];
$total = $_SESSION['total'];

try {
    // Lấy access token từ PayPal
    $paypal = new PayPalConfig();
    $accessToken = $paypal->getAccessToken();

    // Tạo đơn hàng trên PayPal
    $client = new GuzzleHttp\Client();
    $response = $client->post("https://api-m.sandbox.paypal.com/v2/checkout/orders", [
        'headers' => [
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD', // Đổi thành đơn vị tiền tệ bạn muốn
                        'value' => $total,
                    ],
                ],
            ],
        ],
    ]);

    $order = json_decode($response->getBody(), true);
    $paypalOrderId = $order['id'];
    $approvalUrl = $order['links'][1]['href']; // URL để khách hàng thanh toán

    // Lưu order_id PayPal vào session để xử lý bước CAPTURE
    $_SESSION['paypal_order_id'] = $paypalOrderId;

    // Tạo URL mã QR chứa URL thanh toán PayPal
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($approvalUrl) . "&size=200x200";

} catch (Exception $e) {
    die("Lỗi khi tạo đơn hàng PayPal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán qua mã QR</title>
</head>
<body>
    <h1>Quét mã QR để thanh toán</h1>
    <p>Mã đơn hàng: <?php echo $order_id; ?></p>
    <p>Tổng số tiền: <?php echo $total; ?> USD</p>
    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
    <form action="capture_payment.php" method="POST">
        <button type="submit">Kiểm tra trạng thái thanh toán</button>
    </form>
    <a href="../checkout.php">Quay lại</a>
</body>
</html>