<?php
require 'config.php';
require 'send_email.php'; // Include the email sending function

function sendActivationEmail($to, $user_name, $activation_token) {
    $subject = "Kích hoạt tài khoản";
    $activation_link = "http://localhost/note_management/api/activate.php?token=" . $activation_token;

    // Using heredoc for better readability
    $body = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <title>Email Confirmation</title>
</head>
<body style='margin-top:20px;'>
    <table class='body-wrap' style='font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;' bgcolor='#f6f6f6'>
        <tbody>
            <tr>
                <td valign='top'></td>
                <td class='container' width='600' valign='top'>
                    <div class='content' style='padding: 20px;'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;' bgcolor='#fff'>
                            <tbody>
                                <tr>
                                    <td class='' style='font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; background-color: #38414a; padding: 20px;' align='center' bgcolor='#71b6f9' valign='top'>
                                        <a href='#' style='font-size:32px;color:#fff;text-decoration: none;'>Hello, $user_name!</a> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='content-wrap' style='padding: 20px;' valign='top'>
                                        <table width='100%' cellpadding='0' cellspacing='0'>
                                            <tbody>
                                                <tr>
                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                        Are you ready to gain access to all of the assets we prepared for clients of <strong>Note Website</strong>?
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                        First, you must complete your registration by clicking on the button below:
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block' style='text-align: center;' valign='top'>
                                                        <a href="$activation_link" class='btn-primary' style='font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; border-radius: 5px; background-color: #D10024; padding: 8px 16px; display: inline-block;'>Verify my email address</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                        This link will verify your email address, and then you’ll officially be a part of the Note Website community.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                        See you there!
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                        Best regards, the Note Website team.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td valign='top'></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
EOD;

    return sendEmail($to, $subject, $body);
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $data['email'] ?? '';
    $display_name = $data['display_name'] ?? '';
    $password = $data['password'] ?? '';
    $password_confirmation = $data['password_confirmation'] ?? '';

    // Kiểm tra xem email đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Email đã được sử dụng.']);
        exit;
    }

    // Kiểm tra mật khẩu
    if ($password === $password_confirmation) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $activation_token = bin2hex(random_bytes(16));
        
        // Chèn thông tin người dùng vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO users (email, display_name, password, activation_token) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$email, $display_name, $hashed_password, $activation_token])) {
            // Gửi email kích hoạt
            sendActivationEmail($email, $display_name, $activation_token); // Corrected call
            
            // Tự động đăng nhập
            session_start();
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_email'] = $email; // Lưu email vào session
            
            echo json_encode(['message' => 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt.']);
        } else {
            echo json_encode(['message' => 'Có lỗi khi đăng ký tài khoản.']);
        }
    } else {
        echo json_encode(['message' => 'Mật khẩu không khớp.']);
    }
} else {
    echo json_encode(['message' => 'Yêu cầu không hợp lệ.']);
}
?>