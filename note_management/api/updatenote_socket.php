<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = $_POST['note_id'] ?? null;
    $content = $_POST['content'] ?? null;

    if ($note_id === null || $content === null) {
        echo json_encode(['message' => 'Thiếu dữ liệu cần thiết.']);
        exit;
    }

    // Cập nhật ghi chú trong DB
    $stmt = $pdo->prepare("UPDATE notes SET content = ?, modified_at = NOW() WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$content, $note_id, $user_id])) {
        
        // Gửi cập nhật qua WebSocket
        $websocketData = json_encode([
            'type' => 'update_note',
            'note_id' => $note_id,
            'content' => $content
        ]);

        $socket = stream_socket_client("tcp://localhost:8080", $errno, $errstr, 30);
        if ($socket) {
            fwrite($socket, $websocketData);
            fclose($socket);
        }

        echo json_encode(['message' => 'Ghi chú đã được cập nhật thành công.']);
    } else {
        echo json_encode(['message' => 'Cập nhật không thành công.']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không hợp lệ.']);
}
?>
