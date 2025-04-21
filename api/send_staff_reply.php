<?php
header('Content-Type: application/json');
require_once '../server/connection.php'; // Kết nối database

// Đọc và giải mã dữ liệu JSON từ body
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra dữ liệu trước khi truy cập
if (!isset($data['session_id']) || !isset($data['message'])) {
    echo json_encode(['success' => false, 'error' => 'Thiếu dữ liệu']);
    exit;
}

// Lấy dữ liệu từ mảng
$session_id = $data['session_id'];
$message = $data['message'];

// Lưu vào bảng chatbot_messages
$stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, sender, message, timestamp) VALUES (?, 'agent', ?, NOW())");
$stmt->bind_param("is", $session_id, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
?>
