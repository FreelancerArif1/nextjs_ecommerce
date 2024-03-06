-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2022 at 10:25 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biponi_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `division_id`, `title`, `status`) VALUES
(28, 36, 'Cumilla District', 1),
(61, 65, 'Jashore District', 1),
(70, 65, 'Satkhira District', 1),
(71, 68, 'Narsingdi District', 1),
(72, 66, 'Jhalakathi District', 1),
(128, 67, 'Sylhet District', 1),
(140, 67, 'Moulvibazar District', 1),
(146, 69, 'Panchagarh District', 1),
(150, 68, 'Gazipur District', 1),
(160, 60, 'Sirajganj District', 1),
(784, 65, 'Meherpur District', 1),
(987, 36, 'Feni District', 1),
(989, 60, 'Pabna District', 1),
(991, 68, 'Shariatpur District', 1),
(993, 60, 'Bogura District', 1),
(994, 69, 'Dinajpur District', 1),
(995, 65, 'Narail District', 1),
(996, 66, 'Patuakhali District', 1),
(997, 66, 'Pirojpur District', 1),
(998, 36, 'Brahmanbaria District', 1),
(999, 66, 'Barishal District', 1),
(1000, 67, 'Habiganj District', 1),
(1002, 36, 'Rangamati District', 1),
(1004, 60, 'Rajshahi District', 1),
(1006, 36, 'Noakhali District', 1),
(1007, 68, 'Narayanganj District', 1),
(1008, 67, 'Sunamganj District', 1),
(1037, 65, 'Chuadanga District', 1),
(1038, 65, 'Kushtia District', 1),
(1039, 65, 'Magura District', 1),
(1040, 60, 'Natore District', 1),
(1079, 69, 'Lalmonirhat District', 1),
(1080, 69, 'Nilphamari District', 1),
(1081, 69, 'Gaibandha District', 1),
(1185, 60, 'Joypurhat District', 1),
(1187, 66, 'Bhola District', 1),
(1867, 60, 'Chapainawabganj District', 1),
(1928, 69, 'Thakurgaon District', 1),
(1944, 6175, 'Sherpur District', 1),
(1960, 68, 'Tangail District', 1),
(1987, 69, 'Rangpur District', 1),
(1990, 60, 'Naogaon District', 1),
(2001, 6175, 'Mymensingh District', 1),
(2056, 65, 'Khulna District', 1),
(2075, 36, 'Chandpur District', 1),
(2256, 68, 'Kishoreganj District', 1),
(2257, 6175, 'Jamalpur District', 1),
(2258, 68, 'Manikganj District', 1),
(2262, 6175, 'Netrokona District', 1),
(2942, 68, 'Dhaka District', 1),
(3024, 68, 'Munshiganj District', 1),
(3025, 68, 'Rajbari District', 1),
(3026, 68, 'Madaripur District', 1),
(3027, 68, 'Gopalganj District', 1),
(3028, 68, 'Faridpur District', 1),
(3107, 66, 'Barguna District', 1),
(3108, 36, 'Lakshmipur District', 1),
(3152, 36, 'Chattogram District', 1),
(3237, 36, 'Coxsbazar District', 1),
(3238, 36, 'Khagrachhari District', 1),
(3240, 65, 'Bagerhat District', 1),
(3245, 36, 'Bandarban District', 1),
(3247, 65, 'Jhenaidah District', 1),
(3323, 69, 'Kurigram District', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3324;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
