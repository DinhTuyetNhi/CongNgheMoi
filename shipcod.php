<?php
session_start();
include('server/connection.php');

$order_id = '';
$user_name = '';
$user_email = '';
$user_phone = '';
$user_address = '';
$user_city = '';
$order_items = [];
$order_date = '';
$payment_method = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : 'cod'; // mặc định là cod

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Lấy order_id, order_date và thông tin giao hàng từ đơn hàng mới nhất
    $sql = "SELECT order_id, user_phone, user_address, user_city, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        $user_phone = $row['user_phone'];
        $user_address = $row['user_address'];
        $user_city = $row['user_city'];
        $order_date = $row['order_date'];
    }
    $stmt->close();

    // Lấy họ tên và email từ bảng users
    $sql_user = "SELECT user_name, user_email FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($row_user = $result_user->fetch_assoc()) {
        $user_name = $row_user['user_name'];
        $user_email = $row_user['user_email'];
    }
    $stmt_user->close();
}

// Lấy thông tin sản phẩm từ đơn hàng
if ($order_id) {
    $sql_items = "SELECT 
        p.product_image, 
        p.product_name, 
        p.product_color, 
        oi.product_quantity, 
        oi.product_price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    while ($row_item = $result_items->fetch_assoc()) {
        $order_items[] = $row_item;
    }
    $stmt_items->close();
}

// Tính tổng tiền đơn hàng
$order_total = 0;
foreach ($order_items as $item) {
    $order_total += $item['product_price'] * $item['product_quantity'] + 10;
}

// Tính ngày giao hàng dự kiến (cộng 3 và 4 ngày)
$delivery_start = '';
$delivery_end = '';
if ($order_date) {
    $date = new DateTime($order_date);
    $delivery_start = clone $date;
    $delivery_start->modify('+3 days');
    $delivery_end = clone $date;
    $delivery_end->modify('+4 days');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang thanh toán đơn hàng</title>
    <link rel="stylesheet" href="css/style3.css">
</head>
<body>
<div class="container">
        <header>
            <div class="logo">Liceria & Co</div>
            <div class="order-number">Đơn hàng #<?php echo htmlspecialchars($order_id); ?></div>
        </header>
        
        <div class="success-message">
            <div class="success-icon">✓</div>
            <div>
                <h3>Đặt hàng thành công!</h3>
                <p>Cảm ơn bạn đã đặt hàng. Chúng tôi đã gửi email xác nhận đến email của bạn.</p>
            </div>
        </div>
        
        <div class="main-content">
            <div class="left-column">
                <div class="order-details">
                    <h2>Chi tiết đơn hàng</h2>
                    <div class="product-list">
                        <?php if (!empty($order_items)): ?>
                            <?php foreach ($order_items as $item): ?>
                            <div class="product-item">
                                <div class="product-image">
                                    <img src="assets/imgs/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                </div>
                                <div class="product-details">
                                    <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                    <div class="product-variant">Màu: <?php echo htmlspecialchars($item['product_color']); ?></div>
                                    <div class="product-price-qty">
                                        <div class="quantity">Số lượng: <?php echo htmlspecialchars($item['product_quantity']); ?></div>
                                        <div class="price"><?php echo number_format($item['product_price'] * $item['product_quantity'], 0, ',', '.'); ?>₫</div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div>Không có sản phẩm trong đơn hàng.</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="shipping-method">
                        <div class="shipping-icon">🚚</div>
                        <div>
                            <div><strong>Giao hàng tiêu chuẩn</strong> <span class="cod-badge">Thanh toán khi nhận hàng</span></div>
                            <div style="color: #666;">Nhận hàng trong 3-5 ngày làm việc</div>
                        </div>
                    </div>
                    
                    <div class="delivery-estimate">
                        <strong>Dự kiến giao hàng:</strong>
                        <?php
                            if ($delivery_start && $delivery_end) {
                                echo $delivery_start->format('d/m/Y') . ' - ' . $delivery_end->format('d/m/Y');
                            }
                        ?>
                    </div>
                    
                    <div class="instruction">
                        <h3 style="margin-bottom: 10px;">Hướng dẫn thanh toán khi nhận hàng:</h3>
                        <div class="step">
                            <span class="step-number">1</span> Kiểm tra kỹ sản phẩm khi nhận hàng
                        </div>
                        <div class="step">
                            <span class="step-number">2</span> Thanh toán số tiền <?php echo number_format($order_total, 0, ',', '.'); ?> ₫ bằng tiền mặt hoặc chuyển khoản cho nhân viên giao hàng
                        </div>
                        <div class="step">
                            <span class="step-number">3</span> Giữ lại biên nhận làm bằng chứng đã thanh toán
                        </div>
                    </div>
                </div>
                
                <div class="customer-info">
                    <h2>Thông tin khách hàng</h2>
                    <div class="info-row">
                        <div class="info-label">Họ tên:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_name); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_phone); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_email); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa chỉ giao hàng:</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_address . ', ' . $user_city); ?></div>
                    </div>
                </div>
                
                <div class="payment-info">
                    <h2>Phương thức thanh toán</h2>
                    <div style="display: flex; align-items: center;">
                        <div style="font-size: 24px; margin-right: 15px;">
                            <?php if ($payment_method == 'cod'): ?>
                                💵
                            <?php elseif ($payment_method == 'paypal'): ?>
                                💳
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if ($payment_method == 'cod'): ?>
                                <div><strong>Thanh toán khi nhận hàng (COD)</strong></div>
                                <div style="color: #666; font-size: 14px;">Bạn sẽ thanh toán khi nhận được hàng</div>
                            <?php elseif ($payment_method == 'paypal'): ?>
                                <div><strong>Thanh toán qua PayPal</strong></div>
                                <div style="color: #666; font-size: 14px;">Bạn đã thanh toán qua PayPal</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="right-column">
                <div class="summary-card">
                    <div class="summary-row total-row">
                        <div>Tổng thanh toán</div>
                        <div><?php echo number_format($order_total, 0, ',', '.'); ?>₫</div>
                    </div>
                </div>
                
               
                
                <div class="actions">
                    <a href="order_details.php" class="btn btn-primary" style="flex: 1;">Theo dõi đơn hàng</a>
                    <a href="index.php" class="btn btn-outline" style="flex: 1;">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 40px; text-align: center; color: #666; font-size: 14px;">
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email <strong>cskh@shoponline.com</strong> hoặc hotline <strong>1900 1234</strong></p>
            <p style="margin-top: 10px;">© 2025 ShopOnline. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>

    <script>
        // Hiển thị thời gian đặt hàng
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy thông tin đơn hàng từ localStorage (trong thực tế sẽ lấy từ server)
            const orderData = {
                orderNumber: '<?php echo $order_id; ?>',
                orderDate: new Date(),
                items: [
                    {
                        name: 'Áo thun unisex cotton',
                        variant: 'Màu: Trắng / Size: L',
                        price: 160000,
                        quantity: 2,
                        image: '/api/placeholder/80/80'
                    },
                    {
                        name: 'Quần jean nam slimfit',
                        variant: 'Màu: Xanh đậm / Size: 32',
                        price: 450000,
                        quantity: 1,
                        image: '/api/placeholder/80/80'
                    }
                ],
                shipping: 30000,
                tax: 30000,
                discount: 0,
                customer: {
                    name: '<?php echo htmlspecialchars($user_name); ?>',
                    phone: '<?php echo htmlspecialchars($user_phone); ?>',
                    email: '<?php echo htmlspecialchars($user_email); ?>',
                    address: '<?php echo htmlspecialchars($user_address . ', ' . $user_city); ?>'
                }
            };
            
            // Định dạng ngày giao hàng dự kiến (3-5 ngày kể từ ngày đặt)
            const today = new Date();
            const deliveryStartDate = new Date(today);
            deliveryStartDate.setDate(today.getDate() + 3);
            const deliveryEndDate = new Date(today);
            deliveryEndDate.setDate(today.getDate() + 5);
            
            // Hàm định dạng ngày tháng theo chuẩn Việt Nam
            function formatDate(date) {
                return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth()+1).toString().padStart(2, '0')}/${date.getFullYear()}`;
            }
            
            
            // Xử lý sự kiện khi nhấn nút "Theo dõi đơn hàng"
            document.querySelector('.btn-primary').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "order_details.php";
            });
            
            // Xử lý sự kiện khi nhấn nút "Tiếp tục mua sắm"
            document.querySelector('.btn-outline').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "index.php";
            });
        });

    </script>
</body>
</html>