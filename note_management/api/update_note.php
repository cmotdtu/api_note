<?php
require 'config.php';
session_start();

header("Content-Type: application/json"); // Trả về JSON

// Debug: Ghi log dữ liệu nhận được
error_log('📥 Dữ liệu thô nhận được: ' . file_get_contents("php://input"));

// Nhận dữ liệu từ POST hoặc JSON body
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents("php://input"), true);
} else {
    $data = $_POST;
}

// Ghi log dữ liệu đã xử lý
error_log('📥 Dữ liệu sau xử lý: ' . json_encode($data));

// Kiểm tra xem người dùng đã đăng nhập hay chưa
// if (!isset($_SESSION['user_id'])) {
//     echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
//     exit;
// }

// Giả lập user_id cho thử nghiệm (XÓA khi triển khai thực tế)
$user_id = 9;

// Lấy dữ liệu từ request
$note_id = isset($data['note_id']) ? trim($data['note_id']) : null;
$password = isset($data['password']) ? trim($data['password']) : null;
$content = isset($data['content']) ? trim($data['content']) : null;

// Kiểm tra nếu note_id không hợp lệ
if (empty($note_id)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp note_id hợp lệ.']);
    exit;
}

// Khởi tạo mảng để cập nhật các trường
$fields = [];
$params = [];

// Kiểm tra và thêm các trường cần cập nhật
if (!empty($content)) {
    $fields[] = "content = ?";
    $params[] = $content;
}
if (!empty($password)) {
    $fields[] = "password = ?";
    $params[] = $password;
}

// Nếu không có gì để cập nhật
if (empty($fields)) {
    echo json_encode(['success' => false, 'message' => 'Không có dữ liệu cập nhật.']);
    exit;
}

// Thêm trường `modified_at`
$fields[] = "modified_at = ?";
$params[] = date("Y-m-d H:i:s");

// Thêm điều kiện WHERE
$params[] = $note_id;
$params[] = $user_id;

// Tạo truy vấn SQL
$sql = "UPDATE notes SET " . implode(", ", $fields) . " WHERE id = ? AND user_id = ?";

// Ghi log truy vấn để debug
error_log("🛠 SQL Query: $sql");
error_log("🔢 Parameters: " . json_encode($params));

// Cập nhật ghi chú trong database
$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    echo json_encode(['success' => true, 'message' => 'Ghi chú đã được cập nhật thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Cập nhật ghi chú không thành công.']);
}
?>
