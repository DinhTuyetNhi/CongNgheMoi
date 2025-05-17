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

// Hàm tách từ khóa từ câu hỏi khách hàng
function extractKeywords($text) {
    $text = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));
    $stopwords = ['là','có','và','cho','với','của','các','những','một','được','không','thì','làm','ở','khi','để','này','đó','nào','ra','vào','trên','dưới','vì','bằng','hay','như','vậy','sao','ai','gì','đâu','bao','nhiêu','thế','nữa','đã','rồi','chưa','phải','sẽ','cần','muốn','nên'];
    $words = array_filter(explode(' ', $text), function($word) use ($stopwords) {
        return $word && !in_array($word, $stopwords);
    });
    return array_values($words);
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
    // Kiểm tra trạng thái phiên chat
    $status_stmt = $conn->prepare("SELECT status FROM chat_sessions WHERE session_id = ?");
    $status_stmt->bind_param("i", $session_id);
    $status_stmt->execute();
    $status_stmt->bind_result($session_status);
    $status_stmt->fetch();
    $status_stmt->close();

    if ($session_status === 'pending_agent') {
        // Nếu đang chờ nhân viên, chỉ lưu tin nhắn user vào DB, KHÔNG trả về thông báo nữa
        if ($user_id === null) {
            $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, sender, message) VALUES (?, 'user', ?)");
            if (!$stmt) {
                jsonError("Prepare failed (insert user msg): " . $conn->error);
            }
            $stmt->bind_param("is", $session_id, $message);
        } else {
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

        // KHÔNG trả về thông báo nữa, chỉ trả về reply rỗng
        echo json_encode([
            'reply' => ''
        ]);
        exit;
    }

    // Thêm tin nhắn người dùng vào DB
    if ($user_id === null) {
        $stmt = $conn->prepare("INSERT INTO chatbot_messages (session_id, sender, message) VALUES (?, 'user', ?)");
        if (!$stmt) {
            jsonError("Prepare failed (insert user msg): " . $conn->error);
        }
        $stmt->bind_param("is", $session_id, $message);
    } else {
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

    // --- Tìm kiếm thông minh bằng FULLTEXT và AI ---
    $reply = "";
    $found = false;
    $answers = [];
    $scores = [];

    // FULLTEXT search: lấy tối đa 3 đáp án gần nhất
    $stmt = $conn->prepare("SELECT answer, question, MATCH(question) AGAINST (?) AS score 
                            FROM faq_data 
                            WHERE MATCH(question) AGAINST (?) 
                            ORDER BY score DESC LIMIT 3");
    $stmt->bind_param("ss", $message, $message);
    $stmt->execute();
    $stmt->bind_result($answer, $matched_question, $score);

    while ($stmt->fetch()) {
        if ($score > 0) {
            $answers[] = [
                'answer' => $answer,
                'question' => $matched_question,
                'score' => $score
            ];
            $scores[] = $score;
        }
    }
    $stmt->close();

    if (count($answers) === 1) {
        // Chỉ có 1 đáp án nổi bật, trả lời luôn
        $reply = $answers[0]['answer'];
        $found = true;
    } elseif (count($answers) > 1 && (max($scores) - min($scores) > 1)) {
        // Có 1 đáp án nổi bật hơn hẳn, trả lời luôn
        $reply = $answers[0]['answer'];
        $found = true;
    } elseif (count($answers) > 1) {
        // Có nhiều đáp án gần đúng, gửi lên AI để chọn/tổng hợp
        $faqList = "";
        foreach ($answers as $idx => $ans) {
            $faqList .= ($idx+1) . ". " . $ans['question'] . " → " . $ans['answer'] . "\n";
        }
        $ai_prompt = "Khách hỏi: \"$message\"\n"
    . "Dưới đây là các câu trả lời nội bộ, hãy chọn câu phù hợp nhất hoặc tổng hợp thành câu trả lời tốt nhất cho khách. "
    . "Nếu không có thông tin hoặc không chắc chắn, hãy trả lời đúng nguyên văn: 'Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.'\n"
    . $faqList;

        // Gọi OpenRouter AI
        $openrouter_api_key = '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';
        $openrouter_url = "https://openrouter.ai/api/v1/chat/completions";
        $data = [
            "model" => "openai/gpt-3.5-turbo",
            "messages" => [
                ["role" => "system", "content" => "Bạn là trợ lý tư vấn sản phẩm, hãy trả lời ngắn gọn, thân thiện và đúng trọng tâm."],
                ["role" => "user", "content" => $ai_prompt]
            ],
            "max_tokens" => 200,
            "temperature" => 0.7
        ];

        $ch = curl_init($openrouter_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $openrouter_api_key"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        file_put_contents(__DIR__ . '/openrouter_debug.txt', "err: $err\nresult: $result\n", FILE_APPEND);

        if ($err) {
            $reply = "Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.";
        } else {
            $response = json_decode($result, true);
            if (isset($response['choices'][0]['message']['content'])) {
                $reply = trim($response['choices'][0]['message']['content']);
            } else {
                $reply = "Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.";
            }
        }
        $found = true;
    }

    // Nếu không tìm thấy gì, gọi AI như cũ
    if (!$found) {
        $openrouter_api_key = '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';
        $openrouter_url = "https://openrouter.ai/api/v1/chat/completions";
        $data = [
            "model" => "openai/gpt-3.5-turbo",
            "messages" => [
                ["role" => "system", "content" => "Bạn là trợ lý tư vấn sản phẩm, hãy trả lời ngắn gọn, thân thiện và đúng trọng tâm."],
                ["role" => "user", "content" => $message]
            ],
            "max_tokens" => 200,
            "temperature" => 0.7
        ];

        $ch = curl_init($openrouter_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $openrouter_api_key"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        file_put_contents(__DIR__ . '/openrouter_debug.txt', "err: $err\nresult: $result\n", FILE_APPEND);

        if ($err) {
            $reply = "Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.";
        } else {
            $response = json_decode($result, true);
            if (isset($response['choices'][0]['message']['content'])) {
                $reply = trim($response['choices'][0]['message']['content']);
            } else {
                $reply = "Xin lỗi, tôi chưa thể trả lời câu hỏi này. Tôi sẽ chuyển sang nhân viên hỗ trợ.";
            }
        }
    }

    // Nếu vẫn không có câu trả lời hợp lệ, chuyển sang nhân viên như cũ
    if (strpos($reply, "Xin lỗi") === 0) {
        // Kiểm tra đã có handover pending cho session này chưa
        $check_stmt = $conn->prepare("SELECT 1 FROM handover_requests WHERE session_id = ? AND status = 'pending' LIMIT 1");
        $check_stmt->bind_param("i", $session_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        $has_pending = $check_stmt->num_rows > 0;
        $check_stmt->close();

        if (!$has_pending) {
            $reason = "Không tìm thấy câu trả lời phù hợp";
            if ($user_id === null) {
                $stmt_handover = $conn->prepare("INSERT INTO handover_requests (session_id, reason) VALUES (?, ?)");
                if (!$stmt_handover) {
                    jsonError("Prepare failed (handover without user_id): " . $conn->error);
                }
                $stmt_handover->bind_param("is", $session_id, $reason);
            } else {
                $stmt_handover = $conn->prepare("INSERT INTO handover_requests (session_id, user_id, reason) VALUES (?, ?, ?)");
                if (!$stmt_handover) {
                    jsonError("Prepare failed (handover): " . $conn->error);
                }
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
    }

    // Ghi lại câu trả lời của chatbot vào DB
    if (empty($reply) || !is_string($reply)) {
        jsonError("Lỗi: reply không hợp lệ. Giá trị hiện tại: " . var_export($reply, true));
    }

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