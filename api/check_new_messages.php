<?php
require_once '../server/connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Đảm bảo trả về JSON

$session_id = $_GET['session_id'] ?? 0;
$last_id = $_GET['last_id'] ?? 0;
$sender = 'agent'; // Chỉ lấy tin nhắn từ nhân viên

$sql = "SELECT message_id, sender, message, timestamp FROM chatbot_messages 
        WHERE session_id = ? AND message_id > ? AND sender = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode([
        'error' => 'Lỗi prepare SQL: ' . $conn->error
    ]));
}

$stmt->bind_param("iis", $session_id, $last_id, $sender);
$stmt->execute();

$result = $stmt->get_result();
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
