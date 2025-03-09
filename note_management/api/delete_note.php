<?php
require 'config.php'; // Kết nối cơ sở dữ liệu
session_start();

// Kiểm tra phiên đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true); // Lấy dữ liệu từ yêu cầu JSON

    // Kiểm tra dữ liệu
    if (isset($data['note_id'])) {
        $note_id = $data['note_id'];

        // Xóa ghi chú
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        if ($stmt->execute([$note_id])) {
            echo json_encode(['message' => 'Ghi chú đã được xóa thành công.']);
        } else {
            echo json_encode(['message' => 'Xóa ghi chú không thành công.']);
        }
    } else {
        echo json_encode(['message' => 'Vui lòng cung cấp note_id.']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không hợp lệ.']);
}
?>