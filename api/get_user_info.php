<?php
require_once '../server/connection.php';

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Truy vấn để lấy user_id từ session_id
    $getUserIdQuery = "SELECT user_id FROM chat_sessions WHERE session_id = ?";
    $stmt1 = $conn->prepare($getUserIdQuery);
    $stmt1->bind_param("i", $session_id);
    $stmt1->execute();
    $stmt1->bind_result($user_id);
    $stmt1->fetch();
    $stmt1->close();

    if ($user_id) {
        // Lấy info từ users + orders
        $query = "SELECT u.user_name, u.user_email, o.user_phone, o.user_address, o.user_city
                  FROM users u
                  LEFT JOIN orders o ON u.user_id = o.user_id
                  WHERE u.user_id = ?
                  ORDER BY o.order_date DESC
                  LIMIT 1";

        $stmt2 = $conn->prepare($query);
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Không tìm thấy thông tin'], JSON_UNESCAPED_UNICODE);
        }

        $stmt2->close();
    } else {
        echo json_encode(['error' => 'Không tìm thấy user_id'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['error' => 'Thiếu session_id'], JSON_UNESCAPED_UNICODE);
}
?>
