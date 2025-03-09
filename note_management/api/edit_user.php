<?php
require 'config.php'; // Kết nối cơ sở dữ liệu
session_start();

// Kiểm tra phiên đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true); // Lấy dữ liệu từ yêu cầu JSON

    // Khởi tạo mảng để lưu các trường cần cập nhật
    $updateFields = [];
    $params = [];

    // Kiểm tra và thêm các trường vào mảng cập nhật
    if (isset($data['email'])) {
        $updateFields[] = "email = ?";
        $params[] = $data['email'];
    }
    if (isset($data['display_name'])) {
        $updateFields[] = "display_name = ?";
        $params[] = $data['display_name'];
    }
    if (isset($data['password'])) {
        $updateFields[] = "password = ?";
        $params[] = password_hash($data['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    }

    // Nếu không có trường nào được cung cấp
    if (empty($updateFields)) {
        echo json_encode(['message' => 'Vui lòng cung cấp ít nhất một trường để cập nhật.']);
        exit;
    }

    // Thêm user_id vào cuối mảng params
    $params[] = $user_id;

    // Tạo câu lệnh SQL động
    $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    // Thực hiện câu lệnh cập nhật
    if ($stmt->execute($params)) {
        echo json_encode(['message' => 'Thông tin người dùng đã được cập nhật.']);
    } else {
        echo json_encode(['message' => 'Cập nhật thông tin không thành công.']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không hợp lệ.']);
}
?>