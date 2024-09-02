-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 10:41 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_project_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedule`
--

CREATE TABLE `employee_schedule` (
  `id` int(11) NOT NULL,
  `schedule_name` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_fname` varchar(255) DEFAULT NULL,
  `employee_lname` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_schedule`
--

INSERT INTO `employee_schedule` (`id`, `schedule_name`, `employee_id`, `employee_fname`, `employee_lname`, `start_date`, `end_date`) VALUES
(1, 'Day Shift', 5, 'Jose', 'Olanam', '2024-04-29', '2024-05-03'),
(2, 'Mid Shift', 8, 'Pedro', 'Pendoku', '2024-05-13', '2024-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `project_id`, `message`, `created_at`) VALUES
(10, 5, 13, 'You have been added into a new project named Notification Test.', '2024-05-12 19:07:46'),
(12, 8, 15, 'You have been added into a new project named PSM Project.', '2024-05-19 18:25:15'),
(13, 6, 15, 'You have been added into a new project named PSM Project.', '2024-05-19 18:25:15'),
(23, 6, 19, 'The email test project details have been updated.', '2024-05-21 22:21:38'),
(25, 6, 19, 'The email test project details have been updated.', '2024-05-21 22:25:14'),
(26, 3, 20, 'You have been added into a new project named Test Email.', '2024-05-23 16:39:35'),
(27, 6, 20, 'You have been added into a new project named Test Email.', '2024-05-23 16:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`) VALUES
(1, 'Sample Project', '																																&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In elementum, metus vitae malesuada mollis, urna nisi luctus ligula, vitae volutpat massa eros eu ligula. Nunc dui metus, iaculis id dolor non, luctus tristique libero. Aenean et sagittis sem. Nulla facilisi. Mauris at placerat augue. Nullam porttitor felis turpis, ac varius eros placerat et. Nunc ut enim scelerisque, porta lacus vitae, viverra justo. Nam mollis turpis nec dolor feugiat, sed bibendum velit placerat. Etiam in hendrerit leo. Nullam mollis lorem massa, sit amet tincidunt dolor lacinia at.&lt;/span&gt;																											', 5, '2024-02-10', '2024-04-10', 2, '5,3', '2020-12-03 09:56:56'),
(3, 'Sample Project 103', '																								&lt;p&gt;&lt;span style=&quot;color: rgb(13, 13, 13); font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;Sample Project 102 aims to develop an enhanced employee engagement platform for XYZ Corporation. The platform will serve as a comprehensive solution to improve communication, collaboration, and overall engagement among employees across different departments and locations. By leveraging modern technologies and user-centric design principles, the platform will empower employees to connect, share knowledge, recognize achievements, and contribute to a thriving organizational culture.&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;color: rgb(13, 13, 13); font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;Todo:&lt;/span&gt;&lt;/p&gt;&lt;table class=&quot;table table-bordered&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td&gt;asdas&lt;/td&gt;&lt;td&gt;asdasd&lt;/td&gt;&lt;td&gt;asdasd&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;asdasd&lt;/td&gt;&lt;td&gt;asdasd&lt;/td&gt;&lt;td&gt;asdasd&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;p&gt;&lt;span style=&quot;color: rgb(13, 13, 13); font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;br&gt;&lt;/span&gt;											&lt;/p&gt;																				', 5, '2024-03-24', '2024-03-21', 2, '3,4,5,6', '2024-03-24 14:10:04'),
(5, 'Sample Project 3', '						&lt;div style=&quot;line-height: 19px;&quot;&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid est dolores tempore harum optio ipsam nihil ex a fugit ratione incidunt quo qui corporis, odit omnis placeat amet cumque minima.&amp;nbsp;&lt;span style=&quot;font-size: 1rem;&quot;&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid est dolores tempore harum optio ipsam nihil ex a fugit ratione incidunt quo qui corporis, odit omnis placeat amet cumque minima.&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 1rem;&quot;&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid est dolores tempore harum optio ipsam nihil ex a fugit ratione incidunt quo qui corporis, odit omnis placeat amet cumque minima.&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 1rem;&quot;&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid est dolores tempore harum optio ipsam nihil ex a fugit ratione incidunt quo qui corporis, odit omnis placeat amet cumque minima.&lt;/span&gt;&lt;/div&gt;																', 5, '2024-03-01', '2024-04-01', 4, '5,6', '2024-03-31 10:39:28'),
(8, 'Project X', '																																																												&lt;p&gt;Sample Description for Project X&lt;/p&gt;&lt;ol&gt;&lt;li&gt;Project X 1&lt;/li&gt;&lt;li&gt;Project X 2&lt;/li&gt;&lt;/ol&gt;&lt;p&gt;Another Sample Description.&lt;/p&gt;																																																		', 2, '2024-04-08', '2024-05-31', 4, '5,8,6', '2024-04-10 21:58:02'),
(9, 'Project Z', '						Description					', 1, '2024-03-10', '2024-06-10', 4, '5', '2024-04-10 23:24:28'),
(10, 'HRIS System', '&quot;Embark on a seamless transition to efficiency and innovation with our cutting-edge HRIS (Human Resource Information System) project. Our comprehensive solution offers a dynamic platform tailored to streamline HR processes, from recruitment to retirement. Experience enhanced data management, insightful analytics, and intuitive user interfaces, empowering your team to focus on strategic initiatives while we handle the intricacies of HR administration. Revolutionize your workforce management with our bespoke HRIS system &ndash; the cornerstone of tomorrow&amp;#x2019;s HR excellence.&quot;											', 5, '2024-05-09', '2024-07-09', 4, '3', '2024-05-09 17:49:32'),
(15, 'PSM Project', '&lt;p&gt;A project site management system&lt;/p&gt;', 4, '2024-05-19', '2024-05-19', 2, '8,6', '2024-05-19 18:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Project Management System', 'pms@sample.cm', '+631234567890', '803. Chico Street, Lamot 2, Calauan, Laguna', '');

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `description`, `remarks`, `status`, `date_created`) VALUES
(1, 1, 'Sample Task 1', '																																&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-size: 14px; text-align: justify;&quot;&gt;Fusce ullamcorper mattis semper. Nunc vel risus ipsum. Sed maximus dapibus nisl non laoreet. Pellentesque quis mauris odio. Donec fermentum facilisis odio, sit amet aliquet purus scelerisque eget.&amp;nbsp;&lt;/span&gt;																															', NULL, 3, '2020-12-03 11:08:58'),
(5, 3, 'New Task 1', '								Finish this task			', NULL, 3, '2024-03-24 14:33:04'),
(6, 3, 'New Task 2', '																																																								&lt;div style=&quot;line-height: 19px;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium expedita nihil aliquid cupiditate quos voluptatum distinctio voluptatem mollitia cum nobis rerum minus quas iusto eius corrupti, alias minima quo velit??&lt;/div&gt;																																																	', NULL, 3, '2024-03-24 14:48:47'),
(8, 5, 'Database Management Creation', '								Manage and create migrations for the project, discuss the relations and models for the project.						', NULL, 3, '2024-03-31 10:40:37'),
(10, 8, 'Project X Sample Task 1 ', '																																																																																																				Create a wireframe for the project x project. edited																																																																								', NULL, 2, '2024-04-10 22:00:17'),
(11, 8, 'Project X Sample Task 2', '																																												&lt;font color=&quot;rgba(0, 0, 0, 0.81)&quot;&gt;&lt;span style=&quot;font-size: 13.3333px;&quot;&gt;&lt;i&gt;Modify the wireframe created&lt;/i&gt;&lt;/span&gt;&lt;/font&gt;																																	', '				N/A			', 2, '2024-04-10 22:59:06'),
(12, 9, 'project z task', 'Sample Test Task for this project', '				N/A			', 1, '2024-04-11 00:59:46'),
(13, 1, 'Sample Task 2', '												test task									', NULL, 3, '2024-04-28 11:18:47'),
(14, 8, 'Test Task 1', 'Sample Test Task', NULL, 3, '2024-05-07 16:26:21'),
(15, 10, 'User Requirements Gathering', '				Engage stakeholders through interviews to gather insights into existing HR processes and pain points. Document requirements, emphasizing functionality and security, and prioritize them for development and implementation			', 'Revise the task', 3, '2024-05-09 17:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '1712668740_user_avatar.jpg', '2024-03-02 14:11:16'),
(2, 'Josef Gabriel', 'Liu', 'jsmith@sample.com', '0192023a7bbd73250516f069df18b500', 2, 'no-image-available.png', '2024-03-02 14:12:03'),
(3, 'Juan', 'Dela Cruz', 'paolobachicha@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 3, 'no-image-available.png', '2024-03-02 14:12:51'),
(4, 'James', 'Angeles', 'james_angeles@sample.com', '0192023a7bbd73250516f069df18b500', 2, 'no-image-available.png', '2024-03-02 14:13:21'),
(5, 'Jose', 'Olanam', 'jose_onalam@sample.com', '0192023a7bbd73250516f069df18b500', 3, 'no-image-available.png', '2024-03-02 14:13:59'),
(6, 'Wally', 'Aloyab', 'amatsuyu09@gmail.com', '0192023a7bbd73250516f069df18b500', 3, 'no-image-available.png', '2024-03-02 14:14:23'),
(8, 'Pedro', 'Pendoku', 'pedro@sample.com', '6ad14ba9986e3615423dfca256d04e3f', 3, 'no-image-available.png', '2024-04-09 21:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_productivity`
--

CREATE TABLE `user_productivity` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `user_id` int(30) NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_productivity`
--

INSERT INTO `user_productivity` (`id`, `project_id`, `task_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `time_rendered`, `date_created`) VALUES
(5, 3, 5, '&lt;div style=&quot;line-height: 19px;&quot;&gt;&lt;span style=&quot;font-family: Arial;&quot;&gt;Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad vero amet et eveniet consectetur debitis porro minus, architecto culpa, rerum magnam itaque earum. Sunt necessitatibus &lt;/span&gt;&lt;span style=&quot;font-family: Arial;&quot;&gt;﻿&lt;/span&gt;&lt;span style=&quot;font-family: Arial;&quot;&gt;pariatur commodi. Doloremque, veniam nostrum?&lt;/span&gt;&lt;/div&gt;																										', 'ongoing', '2024-03-24', '13:34:00', '17:34:00', 4, 4, '2024-03-24 14:34:23'),
(6, 3, 5, '							&lt;div style=&quot;line-height: 19px;&quot;&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia nihil laboriosam accusamus voluptas, ipsam optio eveniet similique nisi aliquam natus, ullam, ab consectetur possimus exercitationem nemo dolorem sint. Officia, voluptatem.&lt;/div&gt;																																', 'ongoing 2', '2024-03-25', '14:40:00', '15:40:00', 4, 1, '2024-03-24 14:40:04'),
(7, 3, 6, '																																																																											&lt;div&gt;To make the cards go side by side, you can use Bootstrap&rsquo;s grid system. Currently, each card is contained within a &lt;font color=&quot;#0d0d0d&quot; face=&quot;S&ouml;hne Mono, Monaco, Andale Mono, Ubuntu Mono, monospace&quot;&gt;&lt;span style=&quot;border-style: solid; border-color: rgb(227, 227, 227); border-image: initial; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(69,89,164,.5); --tw-ring-offset-shadow: 0 0 transparent; --tw-ring-shadow: 0 0 transparent; --tw-shadow: 0 0 transparent; --tw-shadow-colored: 0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; font-size: 0.875em; white-space-collapse: preserve;&quot;&gt;&lt;b&gt;&amp;lt;div class=&quot;col-md-3&quot;&amp;gt;&lt;/b&gt;&lt;/span&gt;&lt;/font&gt;, which means each card takes up 3 columns on medium-sized screens and above. To have them go side by side, you can place them within a row and adjust the column size accordingly. Here&rsquo;s how you can modify your code:&lt;/div&gt;																																										', 'New Task 2 Ongoing', '2024-03-24', '00:00:00', '05:00:00', 4, 5, '2024-03-24 14:49:18'),
(8, 3, 6, '							asdasdasdaaa', 'ongoing', '2024-03-25', '21:21:00', '23:21:00', 5, 2, '2024-03-25 21:21:28'),
(11, 1, 1, '&lt;p&gt;Done with the pagination task:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;ADDED THE PAGINATION&lt;/li&gt;&lt;li&gt;MODIFIED THE UI&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Link:&lt;br&gt;&lt;i&gt;https://drive.google.com/drive/folders/1ZeJcDH6GtP4ZqoT3Q-8TwJ-4Q9NfBCcm&lt;/i&gt;&lt;br&gt;&lt;/p&gt;', 'Pagination Task', '2024-04-10', '08:00:00', NULL, 3, 8, '2024-04-10 21:10:29'),
(12, 8, 10, '							&lt;p&gt;added the wireframe, for qa. edited&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;link.&lt;/p&gt;&lt;p&gt;&lt;i&gt;&lt;u&gt;sample link&lt;/u&gt;&lt;/i&gt;&lt;/p&gt;						', 'Wireframe', '2024-04-10', '08:00:00', '17:00:00', 1, 9, '2024-04-10 22:01:22'),
(15, 8, 14, 'sample comment/description for the progress', 'Tested the test task', '2024-05-07', '08:00:00', '17:00:00', 1, 9, '2024-05-07 16:27:11'),
(16, 10, 15, 'progress', 'requirements gathering', '2024-05-12', '18:30:00', '20:30:00', 3, 2, '2024-05-12 18:30:15'),
(18, 1, 13, '							Test 2						', 'Sample Task 2 Test 1', '2024-05-18', '08:00:00', '12:00:00', 3, 4, '2024-05-18 00:50:32'),
(19, 8, 14, '							test						', 'test', '2024-05-18', '15:00:00', '17:00:00', 8, 2, '2024-05-18 21:22:43'),
(22, 1, 1, 'wire wire wire', 'Wireframe', '2024-05-19', '15:00:00', NULL, 3, 0, '2024-05-19 18:07:06'),
(24, 1, 1, 'testestet													', 'testestet', '2024-05-19', '10:00:00', '12:00:00', 3, 2, '2024-05-19 18:14:10'),
(25, 10, 15, 'test', 'test', '2024-05-22', '11:50:00', '12:50:00', 3, 1, '2024-05-22 11:50:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_productivity`
--
ALTER TABLE `user_productivity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
