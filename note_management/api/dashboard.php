<?php 
require 'config.php'; // Kết nối cơ sở dữ liệu
session_start();
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT is_active FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !$user['is_active']) {
        echo '<div class="alert alert-warning">Tài khoản chưa được xác minh. Vui lòng kiểm tra email để hoàn tất quá trình kích hoạt.</div>';
    }
}