<?php
require 'config.php';
require 'send_email.php'; // Nhúng tệp gửi email
session_start(); // Khởi động session nếu cần

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ yêu cầu
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['email'])) {
        $email = $data['email'];

        // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Tạo mã xác thực
            $token = bin2hex(random_bytes(50)); // Tạo mã xác thực ngẫu nhiên
            $expires = date("Y-m-d H:i:s", strtotime('+15 minutes')); // Thời gian hết hạn là 30 giây

            // Xóa mã xác thực cũ nếu có
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);

            // Lưu mã xác thực mới vào cơ sở dữ liệu
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
            if (!$stmt->execute([$email, $token, $expires])) {
                echo json_encode(['message' => 'Có lỗi xảy ra khi lưu mã xác thực.']);
                exit;
            }

            // Tạo liên kết đặt lại mật khẩu
            $resetLink = "http://localhost/note_management/api/reset_password_form.php?token=" . $token;

            // Gửi email với liên kết đặt lại mật khẩu
            $subject = "Dat lai mat khau";
            $message = "Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấn vào liên kết sau để đặt lại mật khẩu của bạn: <a href=\"$resetLink\">$resetLink</a>";

            if (sendEmail($email, $subject, $message)) {
                echo json_encode(['message' => 'Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn.']);
            } else {
                echo json_encode(['message' => 'Có lỗi xảy ra khi gửi email.']);
            }
        } else {
            echo json_encode(['message' => 'Email không tồn tại.']);
        }
    } else {
        echo json_encode(['message' => 'Vui lòng cung cấp email.']);
    }
} else {
    echo json_encode(['message' => 'Yêu cầu không hợp lệ.']);
}
?>