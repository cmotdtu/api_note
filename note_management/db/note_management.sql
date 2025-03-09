-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 09, 2025 lúc 05:37 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `note_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_pinned` tinyint(4) DEFAULT 0,
  `category` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `font_size` varchar(20) DEFAULT '16px',
  `note_color` varchar(7) DEFAULT '#ffffff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `title`, `content`, `created_at`, `modified_at`, `is_pinned`, `category`, `tags`, `password`, `image`, `font_size`, `note_color`) VALUES
(29, 9, 'newnewnewnewnewnewnewnewnew', 'content', '2025-03-07 05:50:30', '2025-03-07 02:20:23', 1, 'category', 'testtag', '1234', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(33, 9, 'new', 'content', '2025-03-07 05:55:58', '2025-03-07 05:55:58', 1, 'category', '\"kh\\u1ea9n c\\u1ea5p, deadline\\t\"', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(34, 9, 'new', 'content', '2025-03-07 05:56:16', '2025-03-07 05:56:16', 1, 'category', '\"kh\\u1ea9n c\\u1ea5p, deadline\\t\"', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(35, 9, 'new', 'content', '2025-03-07 05:57:14', '2025-03-07 05:57:14', 1, 'category', '\"kh\\u1ea9n c\\u1ea5p, deadline\\t\"', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(36, 9, 'new', 'content', '2025-03-07 05:58:37', '2025-03-07 05:59:52', 1, 'category', 'newss', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(37, 9, 'new', 'content', '2025-03-07 06:01:01', '2025-03-07 06:01:01', 1, 'category', 'khẩn cấp, deadline', '123456', '[\"uploadsvivo-y100-128gb-(10).jpg\",\"uploadsUntitled-2025-01-31-1245.png\",\"uploads\\u0111otongquat.png\",\"uploadsall.drawio.png\"]', '16px', '#ffffff'),
(38, 9, 'new', 'content', '2025-03-07 06:01:12', '2025-03-07 06:01:12', 1, 'category', 'khẩn cấp, deadline', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(39, 9, 'new', 'content', '2025-03-07 06:46:08', '2025-03-07 06:46:08', 1, 'category', 'khẩn cấp, deadline', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(40, 9, 'new', 'content', '2025-03-07 06:47:04', '2025-03-07 06:47:04', 1, 'category', 'MOiw, nặn', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(41, 9, 'new', 'content', '2025-03-07 06:59:21', '2025-03-07 06:59:21', 1, 'category', 'testtttttt', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff'),
(42, 9, 'new', 'knl', '2025-03-07 07:53:25', '2025-03-09 08:34:18', 1, 'category', 'testtttttt', '123456', '[\"uploads\\/vivo-y100-128gb-(10).jpg\",\"uploads\\/Untitled-2025-01-31-1245.png\",\"uploads\\/\\u0111otongquat.png\",\"uploads\\/all.drawio.png\"]', '16px', '#ffffff');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `note_history`
--

CREATE TABLE `note_history` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `note_history`
--

INSERT INTO `note_history` (`id`, `note_id`, `user_id`, `action`, `timestamp`) VALUES
(1, 42, 9, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 14:46:09'),
(2, 42, 15, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 23:05:59'),
(4, 42, 15, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 23:10:55'),
(5, 42, 15, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 23:12:05'),
(6, 42, 15, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 23:13:25'),
(7, 41, 15, 'Đã chia sẻ ghi chú với quocdatforworkv2@gmail.com', '2025-03-09 23:17:17'),
(8, 42, 15, 'Đã chia sẻ ghi chú với quocdat51930@gmail.com', '2025-03-09 23:18:19'),
(11, 3, 15, 'Đã thu hồi quyền chia sẻ ghi chú 42', '2025-03-09 23:32:03'),
(12, 3, 15, 'Đã thu hồi quyền chia sẻ ghi chú 42', '2025-03-09 23:33:07'),
(13, 42, 15, 'Đã chia sẻ ghi chú với quocdat51930@gmail.com', '2025-03-09 23:35:04'),
(14, 4, 15, 'Đã thu hồi quyền chia sẻ ghi chú 42', '2025-03-09 23:36:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `note_tags`
--

CREATE TABLE `note_tags` (
  `note_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `note_tags`
--

INSERT INTO `note_tags` (`note_id`, `tag_id`) VALUES
(39, 8),
(39, 9),
(40, 10),
(40, 11),
(42, 14);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires`, `created_at`) VALUES
(12, 'quocdat51930@gmail.com', '593305336c3148cdf54e7ec7060c605701186261f86c83747b639ecef81806fac0efba315ba10385b47b5c415ad11394b318', '2025-03-06 15:52:37', '2025-03-06 14:37:37'),
(14, 'quocdatforworkv2@gmail.com', '6cb6bfdf5a8e472d5ce96941bf4c0c450b329f1a93d142e5fa8053976e77fa1b7ee6dc6df078b62b0ee1aad788bf83bca711', '2025-03-06 15:55:44', '2025-03-06 14:40:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shared_notes`
--

CREATE TABLE `shared_notes` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `permission` enum('read','edit') NOT NULL,
  `access_password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `shared_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shared_notes`
--

INSERT INTO `shared_notes` (`id`, `note_id`, `recipient_email`, `permission`, `access_password`, `created_at`, `shared_by`) VALUES
(1, 42, 'quocdatforworkv2@gmail.com', 'edit', 'c14907cc8f', '2025-03-09 23:13:22', 15),
(2, 41, 'quocdatforworkv2@gmail.com', 'edit', 'eb945f0486', '2025-03-09 23:17:13', 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `user_id`, `name`) VALUES
(4, 9, 'Test1\r\n'),
(5, 9, 'New Tag'),
(6, 9, 'New Tag'),
(7, 9, 'New Tag'),
(8, 9, 'khẩn cấp'),
(9, 9, 'deadline'),
(10, 9, 'MOiw'),
(11, 9, 'nặn'),
(14, 9, 'testtttttt'),
(15, 9, 'New Tag heou');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(4) DEFAULT 0,
  `activation_token` varchar(255) DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `image` varchar(50) NOT NULL,
  `theme` enum('light','dark') DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `display_name`, `password`, `is_active`, `activation_token`, `preferences`, `image`, `theme`) VALUES
(1, 'your_email@example.com', 'Your Display Name', '$2y$10$m.Fd31po367En2MLwZkfd.peeTFg9MGQn3WSccQl5IFRpOzspZCva', 1, NULL, NULL, '', 'light'),
(9, 'quocdat51930@gmail.com', 'Test app1', '$2y$10$dqfVulXaHgSxxXrCunAa6eR41aXNdVAFKciIiqJPhN5hqhZdLHLvi', 1, NULL, NULL, '', 'light'),
(15, 'quocdatforworkv2@gmail.com', 'Nguyễn Quốc Đạt', '$2y$10$lpPX0gjAxTxc1MarvEZj7OIbM95WGm4u2HIpHrKKWwTwyl8J5k8yi', 0, '2935445caa9f7e21f5f0b708e93886f3', NULL, '', 'light');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id_notes` (`user_id`);

--
-- Chỉ mục cho bảng `note_history`
--
ALTER TABLE `note_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_id` (`note_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `note_tags`
--
ALTER TABLE `note_tags`
  ADD PRIMARY KEY (`note_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `shared_notes`
--
ALTER TABLE `shared_notes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `note_history`
--
ALTER TABLE `note_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `shared_notes`
--
ALTER TABLE `shared_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_user_id_notes` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `note_history`
--
ALTER TABLE `note_history`
  ADD CONSTRAINT `note_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `note_tags`
--
ALTER TABLE `note_tags`
  ADD CONSTRAINT `note_tags_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
