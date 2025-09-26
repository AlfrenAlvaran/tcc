-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 02:08 PM
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
-- Database: `tcc2024`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(5) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `acc_level` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `fname`, `lname`, `email`, `password`, `acc_level`, `status`, `date_added`) VALUES
(7, 'King', 'San Andress', 'king@gmail.com', '123', 2, 0, '2024-12-05 13:37:37'),
(10, 'King Gian', 'San Andress', 'Tcc.thesis2024@gmail.com', '111', 1, 0, '2024-12-09 14:38:35'),
(11, 'Mc andrei', 'Magpoc', 'mcky@gmail.com', '123', 1, 0, '2025-04-26 14:24:08'),
(18, 'mc', 'magpoc', 'S251007', '1998-12-16', 3, 0, '2025-05-10 19:14:11');

-- --------------------------------------------------------

--
-- Table structure for table `account_level`
--

CREATE TABLE `account_level` (
  `level_id` int(5) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `level_decription` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_level`
--

INSERT INTO `account_level` (`level_id`, `level_name`, `level_decription`) VALUES
(1, 'admin', 'administrator'),
(2, 'staff', 'staff'),
(3, 'student', 'students');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `cur_id` int(5) NOT NULL,
  `cur_year` int(5) NOT NULL,
  `cur_program_id` int(5) NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`cur_id`, `cur_year`, `cur_program_id`, `date_created`) VALUES
(3, 2025, 1, '2024-12-10'),
(4, 2024, 2, '2024-12-10'),
(24, 2025, 3, '2025-05-09'),
(26, 2023, 1, '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_content`
--

CREATE TABLE `curriculum_content` (
  `cc_id` int(5) NOT NULL,
  `curr_id` int(5) NOT NULL,
  `cc_year` int(1) NOT NULL,
  `cc_sem` int(1) NOT NULL,
  `cc_course_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_content`
--

INSERT INTO `curriculum_content` (`cc_id`, `curr_id`, `cc_year`, `cc_sem`, `cc_course_id`) VALUES
(3, 1, 1, 2, 3),
(4, 1, 3, 1, 4),
(5, 1, 4, 2, 4),
(6, 1, 3, 2, 3),
(7, 1, 3, 3, 2),
(9, 1, 2, 2, 2),
(10, 1, 4, 1, 1),
(12, 1, 1, 1, 1),
(15, 1, 1, 1, 2),
(17, 4, 1, 1, 2),
(19, 1, 4, 2, 5),
(20, 1, 4, 2, 0),
(21, 4, 1, 1, 1),
(22, 3, 1, 1, 1),
(23, 3, 1, 1, 3),
(24, 3, 1, 2, 6),
(25, 3, 1, 2, 7),
(26, 3, 2, 1, 4),
(27, 3, 2, 1, 9),
(28, 3, 2, 2, 14),
(29, 3, 2, 2, 2),
(30, 3, 3, 1, 2),
(31, 3, 3, 2, 5),
(32, 3, 3, 3, 1),
(33, 3, 4, 1, 3),
(34, 3, 4, 2, 3),
(35, 3, 4, 1, 7),
(36, 3, 4, 2, 9),
(37, 3, 3, 3, 14),
(38, 3, 3, 2, 3),
(39, 3, 0, 0, 0),
(40, 4, 1, 2, 6),
(41, 4, 1, 2, 1),
(42, 4, 2, 0, 0),
(43, 4, 2, 1, 5),
(44, 4, 2, 2, 9),
(45, 4, 2, 2, 4),
(46, 4, 2, 1, 9),
(47, 4, 3, 1, 9),
(48, 4, 3, 1, 1),
(49, 4, 3, 2, 9),
(50, 4, 3, 2, 3),
(51, 4, 3, 3, 9),
(52, 4, 4, 1, 4),
(53, 4, 4, 2, 6),
(54, 4, 0, 0, 0),
(55, 4, 0, 0, 0),
(56, 3, 3, 1, 9),
(57, 3, 0, 0, 0),
(58, 24, 1, 1, 1),
(59, 24, 1, 2, 3),
(60, 24, 2, 1, 6),
(61, 24, 2, 2, 7),
(62, 24, 3, 1, 4),
(63, 24, 3, 2, 9),
(64, 24, 3, 0, 0),
(65, 24, 3, 3, 14),
(66, 24, 4, 1, 2),
(67, 24, 4, 2, 5),
(68, 24, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(5) NOT NULL,
  `dept_code` varchar(50) NOT NULL,
  `dept_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_students`
--

CREATE TABLE `enrolled_students` (
  `id` int(10) NOT NULL,
  `Student_id` int(10) NOT NULL,
  `Complete_Name` varchar(100) NOT NULL,
  `course` varchar(10) NOT NULL,
  `curriculum` int(10) NOT NULL,
  `cur_id` int(10) NOT NULL,
  `cur_program_id` int(10) NOT NULL,
  `enrolled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolled_students`
--

INSERT INTO `enrolled_students` (`id`, `Student_id`, `Complete_Name`, `course`, `curriculum`, `cur_id`, `cur_program_id`, `enrolled_date`) VALUES
(9, 1001, 'Magpoc, Mc Andrei Escaner', 'BSCS', 2025, 0, 0, NULL),
(10, 1002, 'Magpoc, Kisses Asedillo', 'CHTM', 2025, 0, 0, NULL),
(11, 1005, 'Dela Cruz, Juan M', 'BSCrim', 2025, 0, 0, NULL),
(12, 1004, 'Magpoc, Prinsesa Asedillo', 'CHTM', 2025, 0, 0, NULL),
(13, 1006, 'Dela Cruz, Ron P', 'BSCS', 2025, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(5) NOT NULL,
  `p_code` varchar(50) NOT NULL,
  `p_des` varchar(100) NOT NULL,
  `p_major` int(5) NOT NULL,
  `p_year` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `p_code`, `p_des`, `p_major`, `p_year`) VALUES
(1, 'BSCS', 'Bachelor of Science in Computer Science', 0, 4),
(2, 'BSCrim', 'Bachelor of Science in Criminology', 1, 4),
(3, 'CHTM', 'College of Hospitality and Tourism Management', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `user_id` int(5) NOT NULL,
  `SY` varchar(10) NOT NULL,
  `Student_id` varchar(100) NOT NULL,
  `Student_LName` varchar(50) NOT NULL,
  `Student_FName` varchar(50) NOT NULL,
  `Student_MName` varchar(50) NOT NULL,
  `prog_id` int(5) NOT NULL,
  `complete_addres` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(10) NOT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`user_id`, `SY`, `Student_id`, `Student_LName`, `Student_FName`, `Student_MName`, `prog_id`, `complete_addres`, `province`, `city`, `barangay`, `birthday`, `age`, `gender`) VALUES
(1, 'S25', '1001', 'Magpoc', 'Mc Andrei', 'Escaner', 1, '', '', '', '', NULL, 0, ''),
(1014, 'S25', '1002', 'Magpoc', 'Kisses', 'Asedillo', 2, '', '', '', '', NULL, 0, ''),
(1015, 'S25', '1003', 'Magpoc', 'MC', 'ESCA', 4, '', '', '', '', NULL, 0, ''),
(1016, 'S25', '1004', 'Magpoc', 'Prinsesa', 'Asedillo', 5, '', '', '', '', NULL, 0, ''),
(1017, 'S25', '1005', 'Dela Cruz', 'Juan', 'M', 1, '', '', '', '', NULL, 0, ''),
(1018, 'S25', '1006', 'Dela Cruz', 'Ron', 'P', 1, '', '', '', '', NULL, 0, ''),
(1020, 'S25', '1007', 'magpoc', 'mc', 'andrei', 1, 'b1 305', 'Rizal', 'Cainta', 'San Juan', '1998-12-16', 27, 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `sub_id` int(5) NOT NULL,
  `sub_code` varchar(50) NOT NULL,
  `sub_name` varchar(50) NOT NULL,
  `units` int(1) NOT NULL,
  `withLab` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`sub_id`, `sub_code`, `sub_name`, `units`, `withLab`) VALUES
(1, 'CS 112', 'Computer Programming 1', 3, 1),
(2, 'GE 13', 'Mathematics in Modern World', 3, 1),
(3, 'CS 121', 'Intermediate Programming', 3, 1),
(4, 'CS 311', 'Automata Theory', 3, 0),
(5, 'GE 15', 'Modern Era', 3, 1),
(6, 'CS 122', 'Discrete Structures 1', 3, 0),
(7, 'CS 214', 'Object-Oriented Programming', 3, 1),
(9, 'CS123', 'Programming 3', 5, 1),
(14, 'FIL1', 'FIL', 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_level` (`acc_level`);

--
-- Indexes for table `account_level`
--
ALTER TABLE `account_level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`cur_id`);

--
-- Indexes for table `curriculum_content`
--
ALTER TABLE `curriculum_content`
  ADD PRIMARY KEY (`cc_id`);

--
-- Indexes for table `enrolled_students`
--
ALTER TABLE `enrolled_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `account_id` (`Student_id`),
  ADD KEY `students_ibfk_1` (`prog_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`sub_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `account_level`
--
ALTER TABLE `account_level`
  MODIFY `level_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `cur_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `curriculum_content`
--
ALTER TABLE `curriculum_content`
  MODIFY `cc_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `enrolled_students`
--
ALTER TABLE `enrolled_students`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1021;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `sub_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
