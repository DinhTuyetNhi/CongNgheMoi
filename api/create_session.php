
<?php
require_once '../server/connection.php';
$data = json_decode(file_get_contents('php://input'), true);
$user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;

if ($user_id) {
    $stmt = $conn->prepare("INSERT INTO chat_sessions (user_id, status) VALUES (?, 'active')");
    $stmt->bind_param("i", $user_id);
} else {
    $stmt = $conn->prepare("INSERT INTO chat_sessions (status) VALUES ('active')");
}
if (!$stmt->execute()) {
    echo json_encode(['error' => $stmt->error]);
    exit;
}
$session_id = $stmt->insert_id;
$stmt->close();
echo json_encode(['session_id' => $session_id]);
?>