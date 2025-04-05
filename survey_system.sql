-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 04, 2025 lúc 08:02 PM
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
-- Cơ sở dữ liệu: `survey-system`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `answers`
--

INSERT INTO `answers` (`id`, `response_id`, `question_id`, `option_id`, `created_at`) VALUES
(1, 1, 1, 1, '2025-04-04 07:47:38'),
(2, 1, 2, 1, '2025-04-04 07:47:38'),
(3, 2, 1, 2, '2025-04-04 07:47:38'),
(4, 2, 2, 2, '2025-04-04 07:47:38'),
(5, 3, 3, 1, '2025-04-04 07:47:38'),
(6, 3, 4, 2, '2025-04-04 07:47:38'),
(7, 4, 5, 1, '2025-04-04 07:49:14'),
(8, 4, 6, 2, '2025-04-04 07:49:14'),
(9, 5, 5, 2, '2025-04-04 07:49:14'),
(10, 5, 6, 1, '2025-04-04 07:49:14'),
(11, 6, 7, 3, '2025-04-04 07:49:14'),
(12, 6, 8, 2, '2025-04-04 07:49:14'),
(13, 7, 7, 1, '2025-04-04 07:49:14'),
(14, 7, 8, 4, '2025-04-04 07:49:14'),
(15, 8, 9, 2, '2025-04-04 07:49:14'),
(16, 8, 10, 3, '2025-04-04 07:49:14'),
(17, 9, 9, 4, '2025-04-04 07:49:14'),
(18, 9, 10, 1, '2025-04-04 07:49:14'),
(19, 10, 5, 15, '2025-04-04 08:06:17'),
(29, 16, 5, 15, '2025-04-04 09:13:55'),
(30, 16, 6, 17, '2025-04-04 09:13:57'),
(31, 17, 9, 28, '2025-04-04 14:45:00'),
(32, 17, 10, 31, '2025-04-04 14:45:03'),
(33, 18, 1, 3, '2025-04-04 14:49:48'),
(34, 18, 1, 5, '2025-04-04 14:49:59'),
(35, 18, 2, 4, '2025-04-04 14:50:02'),
(36, 19, 3, 8, '2025-04-04 14:50:11'),
(37, 19, 4, 10, '2025-04-04 14:50:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `options`
--

INSERT INTO `options` (`id`, `question_id`, `content`, `order_num`) VALUES
(1, 1, 'Rất hài lòng', 1),
(2, 1, 'Bình thường', 2),
(3, 1, 'Không hài lòng', 3),
(4, 2, 'Chắc chắn có', 1),
(5, 2, 'Có thể', 2),
(6, 2, 'Không bao giờ', 3),
(7, 3, 'Đúng hẹn', 1),
(8, 3, 'Muộn 1-2 ngày', 2),
(9, 3, 'Muộn hơn 3 ngày', 3),
(10, 4, 'Rất tốt', 1),
(11, 4, 'Tốt', 2),
(12, 4, 'Bình thường', 3),
(13, 4, 'Kém', 4),
(14, 5, 'Thường xuyên', 1),
(15, 5, 'Thỉnh thoảng', 2),
(16, 5, 'Hiếm khi', 3),
(17, 6, 'Có', 1),
(18, 6, 'Không', 2),
(19, 7, 'Ít hơn 1 lần', 1),
(20, 7, '1-3 lần', 2),
(21, 7, '4-6 lần', 3),
(22, 7, 'Hơn 6 lần', 4),
(23, 8, 'Giá cả', 1),
(24, 8, 'Chất lượng sản phẩm', 2),
(25, 8, 'Đánh giá từ khách hàng khác', 3),
(26, 8, 'Thương hiệu', 4),
(27, 9, 'Dưới 1 giờ', 1),
(28, 9, '1-3 giờ', 2),
(29, 9, '4-6 giờ', 3),
(30, 9, 'Hơn 6 giờ', 4),
(31, 10, 'Facebook', 1),
(32, 10, 'Instagram', 2),
(33, 10, 'TikTok', 3),
(34, 10, 'Twitter', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `question_type` enum('multiple_choice','single_choice') DEFAULT 'single_choice',
  `order_num` int(11) NOT NULL,
  `parent_question_id` int(11) DEFAULT NULL,
  `parent_option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `questions`
--

INSERT INTO `questions` (`id`, `survey_id`, `content`, `question_type`, `order_num`, `parent_question_id`, `parent_option_id`) VALUES
(1, 1, 'Bạn có hài lòng với sản phẩm không?', 'single_choice', 1, NULL, NULL),
(2, 1, 'Bạn sẽ giới thiệu sản phẩm này cho người khác chứ?', 'single_choice', 2, NULL, NULL),
(3, 2, 'Thời gian giao hàng có đúng hẹn không?', 'single_choice', 1, NULL, NULL),
(4, 2, 'Bạn đánh giá chất lượng đóng gói như thế nào?', 'multiple_choice', 2, NULL, NULL),
(5, 3, 'Bạn có cảm thấy căng thẳng thường xuyên không?', 'single_choice', 1, NULL, NULL),
(6, 3, 'Bạn có thời gian thư giãn mỗi ngày không?', 'single_choice', 2, NULL, NULL),
(7, 4, 'Bạn mua sắm online bao nhiêu lần mỗi tháng?', 'single_choice', 1, NULL, NULL),
(8, 4, 'Những yếu tố nào ảnh hưởng đến quyết định mua hàng của bạn?', 'multiple_choice', 2, NULL, NULL),
(9, 5, 'Bạn dành bao nhiêu giờ mỗi ngày cho mạng xã hội?', 'single_choice', 1, NULL, NULL),
(10, 5, 'Bạn thường sử dụng mạng xã hội nào nhất?', 'multiple_choice', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `responses`
--

INSERT INTO `responses` (`id`, `survey_id`, `user_id`, `completed`, `created_at`) VALUES
(1, 1, 1, 1, '2025-04-04 07:47:24'),
(2, 1, 2, 1, '2025-04-04 07:47:24'),
(3, 2, 3, 1, '2025-04-04 07:47:24'),
(4, 3, 4, 1, '2025-04-04 07:48:58'),
(5, 3, 5, 1, '2025-04-04 07:48:58'),
(6, 4, 6, 1, '2025-04-04 07:48:58'),
(7, 4, 7, 1, '2025-04-04 07:48:58'),
(8, 5, 8, 1, '2025-04-04 07:48:58'),
(9, 5, 9, 1, '2025-04-04 07:48:58'),
(10, 3, 1, 1, '2025-04-04 08:06:17'),
(16, 3, 12, 1, '2025-04-04 09:13:49'),
(17, 5, 12, 1, '2025-04-04 14:44:53'),
(18, 1, 12, 1, '2025-04-04 14:49:44'),
(19, 2, 12, 1, '2025-04-04 14:50:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `surveys`
--

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 'Khảo sát trải nghiệm khách hàng', 'Khảo sát về mức độ hài lòng của khách hàng với sản phẩm.', 'active', '2025-04-04 07:46:46'),
(2, 'Khảo sát về dịch vụ giao hàng', 'Đánh giá về tốc độ giao hàng và chất lượng dịch vụ.', 'active', '2025-04-04 07:46:46'),
(3, 'Khảo sát về sức khỏe tinh thần', 'Khảo sát này giúp đánh giá mức độ căng thẳng và sức khỏe tinh thần của bạn.', 'active', '2025-04-04 07:48:12'),
(4, 'Khảo sát thói quen mua sắm', 'Đánh giá mức độ thường xuyên và sở thích khi mua sắm online.', 'active', '2025-04-04 07:48:12'),
(5, 'Khảo sát sử dụng mạng xã hội', 'Tìm hiểu thói quen và thời gian sử dụng mạng xã hội của bạn.', 'active', '2025-04-04 07:48:12'),
(6, 'Khảo sát mức độ hài lòng về dịch vụ', '', 'active', '2025-04-04 14:46:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `ip_address`, `created_at`) VALUES
(1, 'user1@example.com', '192.168.1.1', '2025-04-04 07:46:27'),
(2, 'user2@example.com', '192.168.1.2', '2025-04-04 07:46:27'),
(3, 'user3@example.com', '192.168.1.3', '2025-04-04 07:46:27'),
(4, 'user4@example.com', '192.168.1.4', '2025-04-04 07:47:59'),
(5, 'user5@example.com', '192.168.1.5', '2025-04-04 07:47:59'),
(6, 'user6@example.com', '192.168.1.6', '2025-04-04 07:47:59'),
(7, 'user7@example.com', '192.168.1.7', '2025-04-04 07:47:59'),
(8, 'user8@example.com', '192.168.1.8', '2025-04-04 07:47:59'),
(9, 'user9@example.com', '192.168.1.9', '2025-04-04 07:47:59'),
(10, 'user10@example.com', '192.168.1.10', '2025-04-04 07:47:59'),
(12, 'test@example.com', '::1', '2025-04-04 09:13:49');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Chỉ mục cho bảng `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `parent_question_id` (`parent_question_id`);

--
-- Chỉ mục cho bảng `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `survey_id` (`survey_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `responses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`parent_question_id`) REFERENCES `questions` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
