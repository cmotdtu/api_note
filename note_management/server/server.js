const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const axios = require('axios');
const path = require('path');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

const API_URL_VIEW = 'http://localhost/note_management/api/notes.php';
const API_URL_UPDATE = 'http://localhost/note_management/api/update_note.php';
const API_URL_VIEW_NOTE = 'http://localhost/note_management/api/view_note.php';

let notes = {};

// Middleware phục vụ file tĩnh
app.use(express.static(path.join(__dirname, '../public')));

// Xử lý request truy cập theo query parameter `note_id`
app.get('/note_management/share_note', (req, res) => {
    const noteId = req.query.note_id;
    if (!noteId) {
        return res.status(400).send("❌ Thiếu tham số note_id");
    }
    res.sendFile(path.join(__dirname, '../public', 'index.html'));
});

//password_share
async function loadNoteFromApi(noteId, password) {
    try {
        const response = await axios.get(`${API_URL_VIEW_NOTE}?note_id=${noteId}&access_password=${encodeURIComponent(password)}`);

        // Kiểm tra nếu API phản hồi lỗi
        if (!response.data || !response.data.note) {
            console.warn('⚠ Dữ liệu API trả về sai:', response.data.message || "Lỗi không xác định.");
            return { error: true, message: response.data.message || "Mật khẩu không đúng hoặc ghi chú không tồn tại." };
        }

        const note = response.data.note;

        let imageArray = [];
        try {
            imageArray = JSON.parse(note.image || "[]");
            if (!Array.isArray(imageArray)) imageArray = [];
        } catch (error) {
            console.error("❌ Lỗi khi parse 'image':", error);
            imageArray = [];
        }

        const noteData = {
            id: note.id,
            user_id: note.user_id,
            title: note.title || "Không có tiêu đề",
            content: note.content || "Không có nội dung",
            created_at: note.created_at || "Không rõ",
            modified_at: note.modified_at || "Không rõ",
            is_pinned: note.is_pinned || false,
            category: note.category || "Không có",
            tags: note.tags || "Không có",
            permission: note.permission,
            font_size: note.font_size || "16px",
            note_color: note.note_color || "#ffffff",
            image: imageArray,
            message: response.data.message || "Không có thông báo",
        };

        notes[noteId] = noteData;
        console.log(`📜 Ghi chú ${noteId} được tải:`, noteData);
        return noteData;
    } catch (error) {
        console.error('❌ Lỗi khi tải ghi chú:', error.message);
        return { error: true, message: "Lỗi khi kết nối đến máy chủ! Hãy thử lại sau." };
    }
}



// //Password cá nhân ok

// async function loadNoteFromApiPerson(noteId, password) {
//     try {
//         const response = await axios.get(`${API_URL_VIEW}?action=view_note&note_id=${noteId}&password=${encodeURIComponent(password)}`);
        
//         // Kiểm tra nếu API phản hồi lỗi
//         if (!response.data || !response.data.note) {
//             console.warn('⚠ Dữ liệu API trả về sai:', response.data.message || "Lỗi không xác định.");
//             return { error: true, message: response.data.message || "Mật khẩu không đúng hoặc ghi chú không tồn tại." };
//         }

//         const note = response.data.note;

//         let imageArray = [];
//         try {
//             imageArray = JSON.parse(note.image || "[]");
//             if (!Array.isArray(imageArray)) imageArray = [];
//         } catch (error) {
//             console.error("❌ Lỗi khi parse 'image':", error);
//             imageArray = [];
//         }

//         const noteData = {
//             id: note.id,
//             user_id: note.user_id,
//             title: note.title || "Không có tiêu đề",
//             content: note.content || "Không có nội dung",
//             created_at: note.created_at || "Không rõ",
//             modified_at: note.modified_at || "Không rõ",
//             is_pinned: note.is_pinned || false,
//             category: note.category || "Không có",
//             tags: note.tags || "Không có",
//             permission: note.permission,
//             can_edit: note.can_edit,
//             font_size: note.font_size || "16px",
//             note_color: note.note_color || "#ffffff",
//             image: imageArray,
//             message: response.data.message || "Không có thông báo",
//         };

//         notes[noteId] = noteData;
//         console.log(`📜 Ghi chú ${noteId} được tải:`, noteData);
//         return noteData;
//     } catch (error) {
//         console.error('❌ Lỗi khi tải ghi chú:', error.message);
//         return { error: true, message: "Lỗi khi kết nối đến máy chủ! Hãy thử lại sau." };
//     }
// }


async function loadNoteFromApiPerson(noteId, password = "") {
    try {
        let apiUrl = `${API_URL_VIEW}?action=view_note&note_id=${noteId}`;
        
        // Nếu mật khẩu không rỗng, thêm vào API request
        if (password.trim() !== "") {
            apiUrl += `&password=${encodeURIComponent(password)}`;
        }
        console.log("🔗 API URL:", apiUrl); // Log URL để debug


        const response = await axios.get(apiUrl);

        // Kiểm tra phản hồi API
        if (!response.data || !response.data.note) {
            console.warn('⚠ Dữ liệu API trả về sai:', response.data.message || "Lỗi không xác định.");
            return { error: true, message: response.data.message || "Mật khẩu không đúng hoặc ghi chú không tồn tại." };
        }

        const note = response.data.note;

        let imageArray = [];
        try {
            imageArray = JSON.parse(note.image || "[]");
            if (!Array.isArray(imageArray)) imageArray = [];
        } catch (error) {
            console.error("❌ Lỗi khi parse 'image':", error);
            imageArray = [];
        }

        const noteData = {
            id: note.id,
            user_id: note.user_id,
            title: note.title || "Không có tiêu đề",
            content: note.content || "Không có nội dung",
            created_at: note.created_at || "Không rõ",
            modified_at: note.modified_at || "Không rõ",
            is_pinned: note.is_pinned || false,
            category: note.category || "Không có",
            tags: note.tags || "Không có",
            permission: note.permission,
            font_size: note.font_size || "16px",
            note_color: note.note_color || "#ffffff",
            image: imageArray,
            message: response.data.message || "Không có thông báo",
        };

        notes[noteId] = noteData;
        console.log(`📜 Ghi chú ${noteId} được tải:`, noteData);
        return noteData;
    } catch (error) {
        console.error('❌ Lỗi khi tải ghi chú:', error.message);
        return { error: true, message: "Lỗi khi kết nối đến máy chủ! Hãy thử lại sau." };
    }
}

// Xử lý kết nối WebSocket
io.on('connection', (socket) => {
    console.log('🔌 Client connected:', socket.id);

    // Xử lý yêu cầu tải ghi chú
    socket.on('request_note', async ({ noteId, password }) => {
        const noteData = await loadNoteFromApi(noteId, password);
        if (noteData) {
            socket.emit('load_note', noteData); // Gửi toàn bộ object thay vì chỉ `content`
        } else {
            socket.emit('load_note', { error: "Ghi chú không tồn tại hoặc sai mật khẩu." });
        }
    });

    // Xử lý yêu cầu tải ghi chú
    socket.on('request_note_person', async ({ noteId, password }) => {
        const noteData = await loadNoteFromApiPerson(noteId, password);
        if (noteData) {
            socket.emit('load_note', noteData); // Gửi toàn bộ object thay vì chỉ `content`
        } else {
            socket.emit('load_note', { error: "Ghi chú không tồn tại hoặc sai mật khẩu." });
        }
    });

    // Xử lý cập nhật ghi chú
    socket.on('edit_note', async ({ noteId, password, content }) => {
        try {
            const requestData = {
                note_id: noteId,
                password: password,
                content: content
            };

            console.log("📤 Gửi yêu cầu cập nhật:", requestData);

            const response = await axios.post(API_URL_UPDATE, JSON.stringify(requestData), {
                headers: { 'Content-Type': 'application/json' }
            });

            console.log("📥 Phản hồi API cập nhật:", response.data);

            if (response.data && response.data.success) {
                if (notes[noteId]) {
                    notes[noteId].content = content; // Cập nhật nội dung trong bộ nhớ cache
                }
                io.emit('note_updated', { noteId, content }); // Phát sự kiện cho tất cả client
                console.log(`✅ Ghi chú ${noteId} đã được cập nhật.`);
            } else {
                console.error('⚠ Cập nhật ghi chú thất bại:', response.data.message || "Lỗi không xác định.");
                socket.emit('update_failed', { noteId, error: response.data.message || "Cập nhật thất bại." });
            }
        } catch (error) {
            console.error('❌ Lỗi khi cập nhật ghi chú:', error.message);
            socket.emit('update_failed', { noteId, error: "Lỗi máy chủ khi cập nhật ghi chú." });
        }
    });

    socket.on('disconnect', () => {
        console.log('❌ Client disconnected:', socket.id);
    });
});

// Khởi động server
server.listen(3000, () => {
    console.log('🚀 Server đang chạy tại http://localhost:3000');
});
