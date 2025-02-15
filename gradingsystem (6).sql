-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2025 at 09:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`user_id`, `fname`, `mname`, `lname`, `ename`, `nickname`, `age`, `sex`, `birthdate`, `address`, `username`, `password`, `email`, `img`, `bio`) VALUES
(1, 'Eren Sky', 'Mediadero', 'Balsomo', '', 'Eren Sky', '22', 'male', 'October 14 2001', 'oringao', 'scatterbox123', '1234', 'balsomoe101@gmail.com', 'admin_1_1739346123.png', 'putcha kapoy nako kaayo gaiz :<'),
(8, 'Kian', 'Sumagaysay', 'Pablo', '', 'Bataaaa', '11', 'male', '2013-04-21', 'Oringao', 'bebekian2cute', '$2y$10$9rxcGNiSVVN14759hiSzrO8MyKOKVhQ2D42lg..XtaK', NULL, 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'balsomoe101@gmail.com', '08cc99aa8c9ccbffa9d263bb8238542a3b977d477259e40890799038ead31b3debab261f33f11eff6985a8b6daa0dae5fc8a', '2025-02-05 10:49:13', '2025-02-05 01:49:13'),
(2, 'balsomoe101@gmail.com', '2850555dac91974e0889fa99ebd5ea168c7fca6761b65d45e4174d71a4dca46aaa2027e310b2196fc44736da8b1924ab4a3d', '2025-02-05 11:19:45', '2025-02-05 02:19:45'),
(3, 'balsomoe101@gmail.com', 'bc16812b02e2d578c21e07fdcbf5f98e8a0a9c6ac8da564f09404de27d1d6f529916dcd3efd33db06c707d25e1b7e6bd63f2', '2025-02-05 11:34:59', '2025-02-05 02:34:59'),
(4, 'balsomoe101@gmail.com', '6813c040f83ff3f3ceeffc592cc3d0992dbb088c72be8e5cae61985b34f2503d4a14cf197859306a90f7a61bf1f0c5027b11', '2025-02-05 04:52:20', '2025-02-05 02:52:20'),
(5, 'balsomoe101@gmail.com', '4781272227b437e7cc5dbe8e18cc5960e22ae8523fb1fce230f71f2fa3b87fd212c2e51df1478922406f3c2375072ad6364c', '2025-02-05 04:56:05', '2025-02-05 02:56:05'),
(6, 'balsomoe101@gmail.com', 'cf437181ff776892961eee576323bb4025c7638a864fbdbc7047043c41b69594e0353756d9481c2a81ba4105b8d1fdd5f710', '2025-02-05 05:00:35', '2025-02-05 03:00:35'),
(7, 'adg123@gmail.com', '7d939d77bc177a6965f8cad5c84beffbdcbfc7929d34820594b40137edfa2eeec6a4e4505baa3744b002d26165b55ed6ce44', '2025-02-05 05:01:04', '2025-02-05 03:01:04'),
(8, 'balsomoe101@gmail.com', 'e115607bf085fdbbd8c66f0eafcfd96bfc11aedaa61518854ee923c7ca42a6bef67cd6b706911ddaeed246fab4c68a5ed289', '2025-02-05 05:16:17', '2025-02-05 03:16:17'),
(9, 'balsomoe101@gmail.com', 'dc9eed3a20fac21b458f90c788494b8503cbeb411d1e95588e48a01254960ab9a9a5c4e9358a2593fbe516bdd0c8568bdcf2', '2025-02-05 05:17:28', '2025-02-05 03:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `performance_tasks`
--

CREATE TABLE `performance_tasks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `total_marks` bigint(50) DEFAULT NULL,
  `grading_period` enum('First','Second','Third','Fourth') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_tasks`
--

INSERT INTO `performance_tasks` (`id`, `student_id`, `name`, `total_score`, `date`, `subject_id`, `total_marks`, `grading_period`) VALUES
(10, 1, 'kantutan', 12, '2025-02-15', 64, 12, 'First');

-- --------------------------------------------------------

--
-- Table structure for table `quarterly_assessment`
--

CREATE TABLE `quarterly_assessment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `total_marks` bigint(50) DEFAULT NULL,
  `grading_period` enum('First','Second','Third','Fourth') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `pname` varchar(50) NOT NULL,
  `lrn` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `year_level` enum('Grade 9','Grade 10') NOT NULL,
  `section` varchar(20) NOT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`user_id`, `fname`, `mname`, `lname`, `ename`, `nickname`, `pname`, `lrn`, `age`, `sex`, `birthdate`, `address`, `username`, `password`, `email`, `year_level`, `section`, `img`, `bio`) VALUES
(1, 'Eren', 'Sky', 'Balsomo', '', 'Eren Sky', 'Mr. B', '1234567890', '22', 'male', 'October 14 2001', 'Oringao', 'irenyiger123', '1234', 'balslomo121@gmail.com', 'Grade 9', 'section1', 'student_1_1739353117.jpg', 'Alice Goudluck samon system'),
(23, 'Ernie', 'Sky', 'Balsomo', 'Jr.', 'Niggas', 'Mr. Basomol', '123134143516', '12', 'male', '2012-10-14', 'Gemilina', '@es123', '1234', 'balsomoe1010@gmail.com', 'Grade 10', 'Ivy', 'default-profile.jpg', NULL),
(24, 'Adre', 'Brutt', 'Shulck', '', 'DreShulk', 'Mr. Brock', '141234134', '11', 'male', '2013-05-12 00:00:00', 'Oringao', 'Andre123', '$2y$10$mbki8fwOuqE4VMxLgaAJWuUTvIzDTngbz817k06B0nM', 'adre123@gmail.com', 'Grade 9', '1', 'default-profile.jpg', NULL),
(25, 'Maria', 'Silca', 'Kres', '', 'Markres', 'Mrs. Lynn', '1431234451', '11', 'female', '2013-05-12 00:00:00', 'Oringao', 'Maria123', '$2y$10$BQNSGFe9GOqx9Qr6MYXGp.bg8ZuFFU/QNqznwfE8wym', 'mar123@gmail.com', 'Grade 10', '2', 'default-profile.jpg', NULL),
(26, 'Sylvia', 'Jones', 'Brutts', '', 'Sylbru', 'Mr. Stell', '413513513', '11', 'female', '2013-05-12 00:00:00', 'Oringao', 'Sylvia123', '$2y$10$SltcPzECp4leIkrOQ//Tz.GXrFocS4BpNJr5P/eze0h', 'syl123@gmail.com', 'Grade 9', '3', 'default-profile.jpg', NULL),
(27, 'John', 'Dee', 'Jones', '', 'Jojo', 'Mr. Marrie', '624563573', '11', 'male', '2013-05-12 00:00:00', 'Oringao', 'John123', '$2y$10$ysZOiF0VzUHrWv/7AVmhrOcDm9RG6NwXs.kXsY04t6i', 'joh123@gmail.com', 'Grade 10', '4', 'default-profile.jpg', NULL),
(28, 'Adre', 'Brutt', 'Shulck', 'Jr.', 'DreShulky', 'Mr. Brock', '141234134', '11', 'male', '2013-05-12 00:00:00', 'Oringao', 'Andre1234', '$2y$10$YcxmoGRT8QE1FbufXFT/7OVmzNjWxWfRqxfJ5h0G1YD', 'adre1234@gmail.com', 'Grade 9', '1', 'default-profile.jpg', NULL),
(29, 'John', 'Dee', 'Jones', 'V.', 'Jojoy', 'Mr. Marrie', '624563573', '11', 'male', '2013-05-12 00:00:00', 'Oringao', 'John1234', '$2y$10$u0ENs2.ZAl.4S0JNcaTETOtb0NNDWUihR9nX8kWr0DT', 'joh1234@gmail.com', 'Grade 10', '4', 'default-profile.jpg', NULL),
(30, 'Meridith', 'Serna', 'quoirre', '', 'randyyy', 'Lydia', '3123527532573', '10', 'female', '2014-12-12', 'Gemilina', 'wdewr123', '1234', 'msq123@gmail.com', 'Grade 9', 'Ewan', 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`) VALUES
(42, 1, 63),
(43, 23, 63),
(44, 1, 64),
(45, 23, 64),
(46, 1, 65);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(20) NOT NULL,
  `year_level` enum('Grade 9','Grade 10') NOT NULL,
  `section` varchar(20) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `school_year` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `year_level`, `section`, `created_by`, `school_year`) VALUES
(63, 'Filipino', 'Grade 9', 'section1', 1, '2024-2025'),
(64, 'ESP', 'Grade 9', 'section1', 1, '2024-2025'),
(65, 'MAPEH', 'Grade 9', 'section1', 1, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`user_id`, `fname`, `mname`, `lname`, `ename`, `age`, `sex`, `birthdate`, `address`, `nickname`, `username`, `password`, `email`, `img`, `bio`) VALUES
(1, 'Kian', 'Sumagaysay', 'Pablo', '', '11', 'male', 'April 21 2013', 'Oringao', 'Kian Pablo', 'itskianpablo11', '1234', 'kian1234@gmail.com', 'teacher_1_1739350496.jpg', 'Child hero'),
(7, 'Yan', 'Yan', 'Balongcas', '', '22', 'male', '2001-10-14', 'Crossing lapus', 'yanyan', 'yan123', '1234', NULL, 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_collaborations`
--

CREATE TABLE `teacher_collaborations` (
  `collaboration_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_collaborations`
--

INSERT INTO `teacher_collaborations` (`collaboration_id`, `subject_id`, `teacher_id`, `added_by`, `added_date`) VALUES
(1, 64, 7, 1, '2025-02-15 01:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `written_works`
--

CREATE TABLE `written_works` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `total_marks` bigint(50) DEFAULT NULL,
  `grading_period` enum('First','Second','Third','Fourth') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `written_works`
--

INSERT INTO `written_works` (`id`, `student_id`, `name`, `total_score`, `date`, `subject_id`, `total_marks`, `grading_period`) VALUES
(9, 0, 'Story', 100, '2025-02-15', 64, 100, 'Fourth'),
(10, 23, 'tula', 15, '2025-02-15', 63, 15, 'First');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teacher_collaborations`
--
ALTER TABLE `teacher_collaborations`
  ADD PRIMARY KEY (`collaboration_id`),
  ADD UNIQUE KEY `unique_collaboration` (`subject_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `written_works`
--
ALTER TABLE `written_works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `teacher_collaborations`
--
ALTER TABLE `teacher_collaborations`
  MODIFY `collaboration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `written_works`
--
ALTER TABLE `written_works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  ADD CONSTRAINT `performance_tasks_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  ADD CONSTRAINT `quarterly_assessment_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `teachers` (`user_id`);

--
-- Constraints for table `teacher_collaborations`
--
ALTER TABLE `teacher_collaborations`
  ADD CONSTRAINT `teacher_collaborations_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_collaborations_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`user_id`),
  ADD CONSTRAINT `teacher_collaborations_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `teachers` (`user_id`);

--
-- Constraints for table `written_works`
--
ALTER TABLE `written_works`
  ADD CONSTRAINT `written_works_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
