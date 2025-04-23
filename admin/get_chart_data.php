<?php
include('../server/connection.php');

// Lấy dữ liệu tổng số đơn hàng và doanh thu theo tuần
$sql = "
    SELECT 
        WEEK(o.order_date, 1) AS week_number, 
        COUNT(DISTINCT o.order_id) AS total_orders,
        SUM(oi.product_price * oi.product_quantity) AS total_revenue
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY WEEK(o.order_date, 1)
    ORDER BY week_number ASC
";

$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'week' => 'Tuần ' . $row['week_number'],
        'orders' => (int)$row['total_orders'],
        'revenue' => (float)$row['total_revenue']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
