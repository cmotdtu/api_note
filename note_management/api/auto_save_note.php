<?php
require 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Người dùng chưa đăng nhập.']);
    exit;
}

$userId = $_SESSION['user_id'];

// Xử lý yêu cầu POST để tự động lưu nội dung ghi chú
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $noteId = $data['id'] ?? null;
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';

    if ($noteId) {
        // Cập nhật ghi chú đã tồn tại
        $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ?, modified_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $content, $noteId, $userId]);
        echo json_encode(['message' => 'Nội dung ghi chú đã được tự động lưu.']);
    } else {
        // Tạo mới ghi chú nếu chưa có
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content, created_at, modified_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$userId, $title, $content]);
        echo json_encode(['message' => 'Ghi chú mới đã được tạo.']);
    }

    exit;
}
?>