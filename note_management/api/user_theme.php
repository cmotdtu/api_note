<?php
require 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Người dùng chưa đăng nhập.']);
    exit;
}

$userId = $_SESSION['user_id'];

// Xử lý yêu cầu GET để lấy cài đặt chủ đề
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT theme FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userSettings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($userSettings);
    exit;
}

// Xử lý yêu cầu POST để tạo mới hoặc cập nhật cài đặt chủ đề
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $theme = isset($data['theme']) ? $data['theme'] : null;

    if ($theme) {
        // Kiểm tra nếu đã có cài đặt chủ đề, nếu không thì tạo mới
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // Cập nhật cài đặt chủ đề
            $stmt = $pdo->prepare("UPDATE users SET theme = ? WHERE id = ?");
            $stmt->execute([$theme, $userId]);
            echo json_encode(['message' => 'Cài đặt chủ đề đã được cập nhật.']);
        } else {
            // Tạo mới cài đặt chủ đề
            $stmt = $pdo->prepare("INSERT INTO users (id, theme) VALUES (?, ?)");
            $stmt->execute([$userId, $theme]);
            echo json_encode(['message' => 'Cài đặt chủ đề đã được tạo mới.']);
        }
    } else {
        echo json_encode(['message' => 'Chủ đề không hợp lệ.']);
    }

    exit;
}
?>