<?php
require 'config.php';
session_start();

$expired_message = ''; 
$reset = null; 

$token = $_GET['token'] ?? '';
$current_time = date("Y-m-d H:i:s");

if ($token) {
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset) {
        $expires = $reset['expires'];

        if ($current_time > $expires) {
            header("Location: expired_link.php");
            exit;
        }
    } else {
        $expired_message = 'Mã xác thực không hợp lệ';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$reset) {
        http_response_code(400);
        echo json_encode(['message' => 'Mã xác thực không hợp lệ hoặc đã hết hạn.']);
        exit;
    }

    $new_password = $_POST['new_password'] ?? '';

    if (empty($new_password) || strlen($new_password) < 6) {
        http_response_code(400);
        echo json_encode(['message' => 'Vui lòng nhập mật khẩu mới (ít nhất 6 ký tự).']);
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    if ($stmt->execute([$hashed_password, $reset['email']])) {
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        echo json_encode(['message' => 'Mật khẩu đã được cập nhật thành công.']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Có lỗi xảy ra khi cập nhật mật khẩu.']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Lại Mật Khẩu</title>
</head>
<body>
    <h2>Đặt Lại Mật Khẩu</h2>
    <?php if (!isset($reset) || $expired_message || $current_time > ($reset['expires'] ?? '')): ?>
        <p><?php echo htmlspecialchars($expired_message); ?> hoặc liên kết đặt lại mật khẩu đã hết hạn.</p>
    <?php else: ?>
        <form action="reset_password_form.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" name="new_password" required>
            <button type="submit">Cập nhật mật khẩu</button>
        </form>
    <?php endif; ?>
</body>
</html>