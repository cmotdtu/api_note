<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE activation_token = ?");
    if ($stmt->execute([$token])) {
        echo "Tài khoản của bạn đã được kích hoạt thành công!";
    } else {
        echo "Kích hoạt tài khoản không thành công.";
    }
}
?>