<?php
require 'config.php'; // Kết nối tới cơ sở dữ liệu
require 'send_email.php'; // Nhúng tệp gửi email
session_start();

// Hàm tạo mật khẩu ngẫu nhiên
function generateRandomPassword($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Chưa đăng nhập.']);
    exit;
}

// Chia sẻ ghi chú
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'share_note') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['note_id']) && isset($data['recipients']) && isset($data['permission'])) {
        $note_id = $data['note_id'];
        $recipients = $data['recipients']; // Đây là một mảng email
        $permission = $data['permission'];
        $shared_by = $_SESSION['user_id']; // ID của người chia sẻ
        $email_send = $_SESSION['user_email']; // ID của người chia sẻ

        $responses = []; // Mảng để lưu trữ phản hồi cho từng email

        foreach ($recipients as $recipient_email) {
            if (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
                $responses[] = ['email' => $recipient_email, 'message' => 'Email không hợp lệ.'];
                continue;
            }

            // Check if the note has already been shared with this recipient
            $checkStmt = $pdo->prepare("
                SELECT * FROM shared_notes WHERE note_id = ? AND recipient_email = ?
            ");
            $checkStmt->execute([$note_id, $recipient_email]);

            if ($checkStmt->rowCount() > 0) {
                $responses[] = ['email' => $recipient_email, 'message' => 'Ghi chú đã được chia sẻ với bạn trước đó.'];
                continue; // Skip to the next recipient
            }

            $access_password = generateRandomPassword();
            $stmt = $pdo->prepare("
                INSERT INTO shared_notes (note_id, recipient_email, permission, access_password, shared_by, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            if ($stmt->execute([$note_id, $recipient_email, $permission, $access_password, $shared_by])) {
                // Prepare email content
                $note_link = "http://localhost:3000/note_management/share_note?note_id=" . $note_id;
                $subject = "Một ghi chú đã được chia sẻ với bạn - $email_send";
                $body = <<<EOD
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Email Share Note</title>
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
                                                        <a href='#' style='font-size:32px;color:#fff;text-decoration: none;'>Hello, $recipient_email!</a> <br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-wrap' style='padding: 20px;' valign='top'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'>
                                                            <tbody>
                                                                <tr>
                                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                                        You have been invited to a Share Note project.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                                        You have been granted shared access with $permission permissions.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='content-block' style='text-align: center;' valign='top'>
                                                                        <a class='btn-primary' style='font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; border-radius: 5px; background-color: #D10024; padding: 8px 16px; display: inline-block;'>$access_password</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                                        Please copy and paste this link into your browser's address bar: 
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='content-block' style='padding: 0 0 20px;' valign='top'>
                                                                        <a href="$note_link">$note_link</a> 
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

                if (sendEmail($recipient_email, $subject, $body)) {
                    // Ghi vào bảng lịch sử
                    $historyStmt = $pdo->prepare("
                        INSERT INTO note_history (note_id, user_id, action)
                        VALUES (?, ?, ?)
                    ");
                    $action_message = 'Đã chia sẻ ghi chú với ' . $recipient_email;

                    $historyStmt->execute([$note_id, $_SESSION['user_id'], $action_message]);

                    $responses[] = ['email' => $recipient_email, 'message' => 'Email đã được gửi.'];
                } else {
                    $responses[] = ['email' => $recipient_email, 'message' => 'Ghi chú đã được chia sẻ nhưng không thể gửi email.'];
                }
            } else {
                $responses[] = ['email' => $recipient_email, 'message' => 'Chia sẻ ghi chú không thành công.'];
            }
        }

        echo json_encode($responses);
    } else {
        echo json_encode(['message' => 'Vui lòng cung cấp note_id, recipients và permission.']);
    }
}

// Hàm để lấy ghi chú đã chia sẻ cùng với thông tin chủ sở hữu
function getSharedNotesWithOwner($pdo, $user_email) {
    $stmt = $pdo->prepare("
        SELECT sn.id, sn.note_id, n.title, sn.recipient_email, sn.permission, sn.access_password, 
               u.display_name AS shared_by, u2.display_name AS owner, sn.created_at
        FROM shared_notes sn
        JOIN notes n ON sn.note_id = n.id
        JOIN users u ON sn.shared_by = u.id
        JOIN users u2 ON n.user_id = u2.id  
        WHERE sn.recipient_email = ?
    ");
    $stmt->execute([$user_email]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kiểm tra phương thức GET để lấy ghi chú đã chia sẻ cùng với thông tin chủ sở hữu
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_shared_notes') {
    $user_email = $_SESSION['user_email']; // Giả sử bạn lưu email trong session
    $shared_notes = getSharedNotesWithOwner($pdo, $user_email);
    echo json_encode($shared_notes);
    exit;
}

// Sửa ghi chú đã chia sẻ
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'edit_shared_note') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Kiểm tra các tham số cần thiết
    if (isset($data['shared_note_id'], $data['id'], $data['permission'])) {
        $note_id = $data['shared_note_id'];
        $id_share = $data['id'];
        $new_permission = $data['permission'];

        // Cập nhật quyền truy cập ghi chú
        $stmt = $pdo->prepare("UPDATE shared_notes SET permission = ? WHERE id = ? AND note_id = ?");
        $historyStmt = $pdo->prepare("
            INSERT INTO note_history (note_id, user_id, action)
            VALUES (?, ?, ?)
        ");

        // Ghi lại hành động chỉnh sửa quyền
        $action_message = 'Đã chỉnh sửa quyền truy cập ghi chú thành ' . $new_permission;

        if ($stmt->execute([$new_permission, $id_share, $note_id]) && $historyStmt->execute([$note_id, $_SESSION['user_id'], $action_message])) {
            echo json_encode(['message' => 'Cập nhật thành công.']);
        } else {
            echo json_encode(['message' => 'Cập nhật không thành công.']);
        }
    } else {
        echo json_encode(['message' => 'Thiếu tham số cần thiết.']);
    }
    exit;
}
// Xóa ghi chú đã chia sẻ
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_shared_note') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Kiểm tra các tham số cần thiết
    if (isset($data['shared_note_id'], $data['id'])) {
        $note_id = $data['id'];
        $id_share = $data['shared_note_id'];

        // Câu lệnh xóa ghi chú chia sẻ
        $stmt = $pdo->prepare("DELETE FROM shared_notes WHERE id = ? AND note_id = ?");
        
        // Thực hiện xóa
        if ($stmt->execute([$note_id,$id_share])) {
            // Ghi vào bảng lịch sử chỉ sau khi xóa thành công
            $historyStmt = $pdo->prepare("
                INSERT INTO note_history (note_id, user_id, action)
                VALUES (?, ?, ?)
            ");
            $action_message = 'Đã thu hồi quyền chia sẻ ghi chú ' . $id_share;

            // Ghi vào bảng lịch sử
            $historyStmt->execute([$note_id, $_SESSION['user_id'], $action_message]);

            echo json_encode(['message' => 'Đã thu hồi quyền chia sẻ ghi chú thành công.']);
        } else {
            echo json_encode(['message' => 'Thu hồi quyền chia sẻ ghi chú không thành công.']);
        }
    } else {
        echo json_encode(['message' => 'Thiếu tham số cần thiết.']);
    }
    exit;
}
?>