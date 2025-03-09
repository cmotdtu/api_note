<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['note_id']) && isset($data['password'])) {
        $note_id = $data['note_id'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("UPDATE notes SET password = ? WHERE id = ? AND user_id = ?");
        if ($stmt->execute([$password, $note_id, $_SESSION['user_id']])) {
            echo json_encode(['message' => 'Mật khẩu ghi chú đã được thiết lập.']);
        } else {
            echo json_encode(['message' => 'Thiết lập mật khẩu không thành công.']);
        }
    } else {
        echo json_encode(['message' => 'Vui lòng cung cấp note_id và password.']);
    }
}
?>