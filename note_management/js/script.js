document.addEventListener("DOMContentLoaded", function () {
    fetchNotes();
    setupWebSocket();
});

function fetchNotes() {
    fetch("../api/notes.php?action=get_notes")
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById("notes-container");
            container.innerHTML = ""; 
            data.forEach(note => {
                let div = document.createElement("div");
                div.classList.add("note");
                div.innerHTML = `<h3>${note.title}</h3><p>${note.content}</p>`;
                container.appendChild(div);
            });
        })
        .catch(error => console.error("Lỗi tải ghi chú:", error));
}

function setupWebSocket() {
    let socket = new WebSocket("ws://localhost:8080");

    socket.onopen = () => console.log("✅ WebSocket connected!");
    socket.onmessage = (event) => {
        console.log("📩 Cập nhật từ server:", event.data);
        fetchNotes(); // Load lại danh sách khi có thay đổi
    };
    socket.onerror = (error) => console.error("❌ WebSocket error:", error);
    socket.onclose = () => console.log("🔴 WebSocket disconnected.");
}

function logout() {
    fetch("../api/logout.php")
        .then(() => {
            window.location.href = "login.html";
        })
        .catch(error => console.error("Lỗi đăng xuất:", error));
}
