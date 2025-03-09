<?php
require 'config.php';

session_start(); // Khởi động session ở đầu tệp

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['email']) && isset($data['password'])) {
        $email = $data['email'];
        $password = $data['password'];

        // Kiểm tra thông tin đăng nhập
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email']; // Lưu email vào session
            var_dump($_SESSION); // Xem nội dung của session
            echo json_encode(['message' => 'Đăng nhập thành công.']);
        } else {
            echo json_encode(['message' => 'Thông tin đăng nhập không hợp lệ.']);
        }
    } else {
        echo json_encode(['message' => 'Vui lòng cung cấp email và mật khẩu.']);
    }
} else {
    echo json_encode(['message' => 'Yêu cầu không hợp lệ.']);
}
?>