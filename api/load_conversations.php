<?php 
require_once '../server/connection.php';

$sql = "
    SELECT 
    hr.session_id,
    u.user_name,
    cm.message AS last_message,
    cm.timestamp AS last_time
FROM handover_requests hr
JOIN chat_sessions cs ON hr.session_id = cs.session_id
JOIN users u ON hr.user_id = u.user_id
LEFT JOIN (
    SELECT 
        session_id, 
        message, 
        timestamp
    FROM chatbot_messages
    WHERE (session_id, timestamp) IN (
        SELECT session_id, MAX(timestamp)
        FROM chatbot_messages
        GROUP BY session_id
    )
) cm ON hr.session_id = cm.session_id
WHERE hr.status = 'pending'
ORDER BY cm.timestamp DESC;
";

$result = $conn->query($sql);
$conversations = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $conversations[] = $row;
    }
}

foreach ($conversations as $conv) {
    $avatar = strtoupper(substr($conv["user_name"], 0, 1));
    $time = date('H:i', strtotime($conv["last_time"] ?? 'now'));

    echo '<li class="list-group-item conversation-item" data-session-id="' . htmlspecialchars($conv["session_id"]) . '">';
    echo '  <div class="d-flex">';
    echo '    <div class="avatar">' . $avatar . '</div>';
    echo '    <div class="conversation-details">';
    echo '      <div class="name-time">';
    echo '        <div class="user-name">' . htmlspecialchars($conv["user_name"]) . '</div>';
    echo '        <div class="time">' . $time . '</div>';
    echo '      </div>';
    echo '      <div class="last-message">' . htmlspecialchars($conv["last_message"]) . '</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</li>';
}

$conn->close();
?>
