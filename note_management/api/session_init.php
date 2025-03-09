<?php
session_start(); // Bắt đầu session
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null; // Đảm bảo biến tồn tại
    $_SESSION['user_email'] = null;
}
?>
