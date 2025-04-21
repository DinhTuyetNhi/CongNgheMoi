<?php

function jsonError($message) {
    http_response_code(500);
    echo json_encode(['error' => $message]);
    exit;
}

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../server/connection.php';

if (!$conn) {
    echo json_encode(['error' => 'Kết nối DB thất bại: ' . mysqli_connect_error()]);
    exit;
}

// Nhận dữ liệu POST
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['message']) || !isset($data['session_id'])) {
    echo json_encode(['error' => 'Thiếu dữ liệu message hoặc session_id']);
    exit;
}

$message = trim($data['message']);
$session_id = (int)$data['session_id'];
$user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;

try {
    // Thêm tin nhắn người dùng vào DB
    if ($user_id === null) {
        // Nếu không có user_id, chỉ lưu session_id
        $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, sender, message) VALUES (?, 'user', ?)");
        if (!$stmt) {
            jsonError("Prepare failed (insert user msg): " . $conn->error);
        }
        $stmt->bind_param("is", $session_id, $message);
    } else {
        // Nếu có user_id
        $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, user_id, sender, message) VALUES (?, ?, 'user', ?)");
        if (!$stmt) {
            jsonError("Prepare failed (insert user msg): " . $conn->error);
        }
        $stmt->bind_param("iis", $session_id, $user_id, $message);
    }
    
    if (!$stmt->execute()) {
        jsonError("Execute failed (insert user msg): " . $stmt->error);
    }
    $stmt->close();

    // Tìm câu trả lời từ bảng FAQ
    $reply = "";
    $lowercaseMessage = strtolower($message);
    $stmt = $conn->prepare("SELECT answer FROM faq_data WHERE LOWER(question) LIKE CONCAT('%', ?, '%') LIMIT 1");
    if (!$stmt) {
        jsonError("Prepare failed (FAQ): " . $conn->error);
    }
    $stmt->bind_param("s", $lowercaseMessage);
    $stmt->execute();
    $stmt->bind_result($answer);
    $found = $stmt->fetch();
    $stmt->close(); // Close here after fetch, before any conditionals

    if ($found) {
        $reply = $answer;
    } else {
        // Nếu không tìm thấy, yêu cầu chuyển sang nhân viên
        $reply = "Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.";

        // Thêm yêu cầu chuyển tiếp
        $stmt_handover = $conn->prepare("INSERT INTO handover_requests (session_id, user_id, reason) VALUES (?, ?, ?)");
        if (!$stmt_handover) {
            jsonError("Prepare failed (handover): " . $conn->error);
        }
        $reason = "Không tìm thấy câu trả lời phù hợp";
        
        // Fix user_id handling for handover
        if ($user_id === null) {
            // If user_id is null, use a different query that doesn't include user_id
            $stmt_handover->close();
            $stmt_handover = $conn->prepare("INSERT INTO handover_requests (session_id, reason) VALUES (?, ?)");
            if (!$stmt_handover) {
                jsonError("Prepare failed (handover without user_id): " . $conn->error);
            }
            $stmt_handover->bind_param("is", $session_id, $reason);
        } else {
            $stmt_handover->bind_param("iis", $session_id, $user_id, $reason);
        }
        
        if (!$stmt_handover->execute()) {
            jsonError("Execute failed (handover): " . $stmt_handover->error);
        }
        $stmt_handover->close();

        // Cập nhật trạng thái phiên chat sang pending_agent
        $stmt_status = $conn->prepare("UPDATE chat_sessions SET status = 'pending_agent' WHERE session_id = ?");
        if (!$stmt_status) {
            jsonError("Prepare failed (update session): " . $conn->error);
        }
        $stmt_status->bind_param("i", $session_id);
        if (!$stmt_status->execute()) {
            jsonError("Execute failed (update session): " . $stmt_status->error);
        }
        $stmt_status->close();
    }

    // Ghi lại câu trả lời của chatbot vào DB
    if (empty($reply) || !is_string($reply)) {
        jsonError("Lỗi: reply không hợp lệ. Giá trị hiện tại: " . var_export($reply, true));
    }

    // Lưu câu trả lời của bot tùy thuộc vào user_id
    if ($user_id === null) {
        $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, sender, message) VALUES (?, 'chatbot', ?)");
        if (!$stmt) {
            jsonError("Prepare failed (insert bot msg without user_id): " . $conn->error);
        }
        $stmt->bind_param("is", $session_id, $reply);
    } else {
        $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, user_id, sender, message) VALUES (?, ?, 'chatbot', ?)");
        if (!$stmt) {
            jsonError("Prepare failed (insert bot msg): " . $conn->error);
        }
        $stmt->bind_param("iis", $session_id, $user_id, $reply);
    }
    
    if (!$stmt->execute()) {
        jsonError("Execute failed (insert bot msg): " . $stmt->error);
    }
    $stmt->close();

    echo json_encode([
        'reply' => $reply
    ]);
    
} catch (Exception $e) {
    jsonError("Lỗi: " . $e->getMessage());
} finally {
    // Đảm bảo đóng kết nối khi hoàn thành
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>