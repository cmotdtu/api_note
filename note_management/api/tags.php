<?php
require 'config.php'; // Kết nối tới cơ sở dữ liệu
session_start();

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

try {
    // **1. Xem danh sách nhãn (GET)**
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list_tags') {
        $stmt = $pdo->prepare("SELECT * FROM tags WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tags);
        exit;
    }

    // **2. Thêm nhãn mới (POST)**
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_tag') {
        $data = json_decode(file_get_contents("php://input"), true);
        $tag_name = $data['name'] ?? '';

        if (!empty($tag_name)) {
            $stmt = $pdo->prepare("INSERT INTO tags (name, user_id) VALUES (?, ?)");
            $stmt->execute([$tag_name, $_SESSION['user_id']]);
            echo json_encode(['message' => 'Nhãn đã được thêm.']);
        } else {
            echo json_encode(['message' => 'Tên nhãn không hợp lệ.']);
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'rename_tag') {
        $data = json_decode(file_get_contents("php://input"), true);
        $tag_id = $data['tag_id'] ?? '';
        $new_name = $data['new_name'] ?? '';
    
        if (!empty($tag_id) && !empty($new_name)) {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $pdo->beginTransaction();
    
            try {
                // 1. Lấy tên nhãn cũ từ bảng tags trước khi cập nhật
                $stmt = $pdo->prepare("SELECT name FROM tags WHERE id = ? AND user_id = ?");
                $stmt->execute([$tag_id, $_SESSION['user_id']]);
                $old_name = $stmt->fetchColumn();
    
                if (!$old_name) {
                    throw new Exception("Không tìm thấy nhãn với ID đã cung cấp.");
                }
    
                // 2. Cập nhật tên nhãn trong bảng tags
                $stmt = $pdo->prepare("UPDATE tags SET name = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$new_name, $tag_id, $_SESSION['user_id']]);
    
                // 3. Cập nhật tên nhãn trong bảng notes (nếu bảng này lưu trực tiếp danh sách nhãn là chuỗi)
                $stmt = $pdo->prepare("
                    UPDATE notes 
                    SET tags = REPLACE(tags COLLATE utf8mb4_unicode_ci, ? COLLATE utf8mb4_unicode_ci, ?)
                    WHERE FIND_IN_SET(? COLLATE utf8mb4_unicode_ci, tags COLLATE utf8mb4_unicode_ci) > 0 
                    AND user_id = ?
                ");
                $stmt->execute([$old_name, $new_name, $old_name, $_SESSION['user_id']]);
    
                // Commit transaction nếu không có lỗi
                $pdo->commit();
                echo json_encode(['message' => 'Nhãn đã được đổi tên thành công trong cả hai bảng.']);
            } catch (Exception $e) {
                // Rollback nếu có lỗi
                $pdo->rollBack();
                echo json_encode(['message' => 'Lỗi khi cập nhật nhãn.', 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Thông tin không đầy đủ.']);
        }
        exit;
    }
    
    // **4. Xóa nhãn (DELETE)**
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_tag') {
        $data = json_decode(file_get_contents("php://input"), true);
        $tag_id = $data['tag_id'] ?? '';

        if (!empty($tag_id)) {
            $stmt = $pdo->prepare("DELETE FROM tags WHERE id = ? AND user_id = ?");
            $stmt->execute([$tag_id, $_SESSION['user_id']]);
            echo json_encode(['message' => 'Nhãn đã được xóa.']);
        } else {
            echo json_encode(['message' => 'Thông tin không đầy đủ.']);
        }
        exit;
    }

    // **5. Lọc ghi chú theo nhãn (GET)**
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'filter_notes_by_tag') {
    $tag_id = $_GET['tag_id'] ?? '';
    
    if (!empty($tag_id)) {
        // Lấy ghi chú theo nhãn
        $stmt = $pdo->prepare("SELECT notes.* FROM notes 
            JOIN note_tags ON notes.id = note_tags.note_id 
            WHERE note_tags.tag_id = ? AND notes.user_id = ?");
        $stmt->execute([$tag_id, $_SESSION['user_id']]);

        // Kiểm tra xem có ghi chú nào được tìm thấy không
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($notes) {
            echo json_encode($notes);
        } else {
            echo json_encode(['message' => 'Không tìm thấy ghi chú nào liên quan đến nhãn này.']);
        }
    } else {
        echo json_encode(['message' => 'Thông tin không đầy đủ.']);
    }
    exit;
}
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Lỗi khi lưu dữ liệu: ' . htmlspecialchars($e->getMessage())]);
}
?>