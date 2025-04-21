<?php
require_once '../server/connection.php';

if (!isset($_GET['session_id'])) {
  http_response_code(400);
  echo "Thiếu session_id";
  exit();
}

$sessionId = $_GET['session_id'];

// Truy vấn tin nhắn
$sql = "SELECT sender, message, timestamp FROM chatbot_messages 
        WHERE session_id = ? 
        ORDER BY timestamp ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sessionId);
$stmt->execute();
$result = $stmt->get_result();

$html = "";

while ($row = $result->fetch_assoc()) {
  $sender = htmlspecialchars($row['sender']);
  $message = nl2br(htmlspecialchars($row['message']));
  $time = date("H:i", strtotime($row['timestamp']));

  if ($sender === 'user') {
    $html .= "
      <div class='d-flex mb-2'>
        <div class='me-2'>
          <span class='badge bg-secondary'>KH</span>
        </div>
        <div>
          <div class='bg-light p-2 rounded border'>{$message}</div>
          <small class='text-muted'>{$time}</small>
        </div>
      </div>
    ";
  } elseif ($sender === 'chatbot') {
    $html .= "
      <div class='d-flex mb-2'>
        <div class='me-2'>
          <span class='badge bg-info text-dark'>Bot</span>
        </div>
        <div>
          <div class='bg-white p-2 rounded border border-info'>{$message}</div>
          <small class='text-muted'>{$time}</small>
        </div>
      </div>
    ";
  } elseif ($sender === 'agent') {
    $html .= "
      <div class='d-flex mb-2 justify-content-end'>
        <div>
          <div class='bg-primary text-white p-2 rounded'>{$message}</div>
          <small class='text-muted'>{$time}</small>
        </div>
      </div>
    ";
  }
}

echo $html;
