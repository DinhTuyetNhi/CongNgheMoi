
<?php
require_once '../server/connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$session_id = $data['session_id'] ?? 0;
$status = $data['status'] ?? '';

if (!$session_id || !$status) {
    echo json_encode(['success' => false, 'error' => 'Thiếu dữ liệu']);
    exit;
}

// Cập nhật trạng thái chat_sessions
$stmt = $conn->prepare("UPDATE chat_sessions SET status = ? WHERE session_id = ?");
$stmt->bind_param("si", $status, $session_id);
$ok = $stmt->execute();
$stmt->close();

// Nếu chuyển về 'active' thì cập nhật handover_requests thành 'resolved'
if ($ok && $status === 'active') {
    $conn->query("UPDATE handover_requests SET status = 'resolved' WHERE session_id = $session_id AND status = 'pending'");
}

echo json_encode(['success' => $ok]);
?>