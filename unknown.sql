-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 20, 2025 at 06:30 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unknown`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` int NOT NULL,
  `municipal_id` int NOT NULL,
  `province_id` int NOT NULL,
  `barangay_name` varchar(250) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `geojson` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `municipal_id`, `province_id`, `barangay_name`, `date_added`, `geojson`) VALUES
(4, 12, 12, 'San Miguel', '2025-07-16 04:45:34', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.980648,10.290015],[124.978032,10.289614],[124.976756,10.286644],[124.976585,10.283393],[124.977614,10.281197],[124.979543,10.280395],[124.981902,10.280648],[124.98168,10.283618],[124.980801,10.286637],[124.980737,10.288516],[124.980648,10.290015]]]}}'),
(5, 12, 12, 'Looc', '2025-07-16 04:49:59', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.983707,10.263267],[124.979204,10.262845],[124.977917,10.2601],[124.978003,10.257482],[124.978132,10.255413],[124.978904,10.253935],[124.981605,10.253681],[124.983363,10.254441],[124.98435,10.255582],[124.983792,10.256511],[124.982806,10.258622],[124.983106,10.260649],[124.983664,10.26162],[124.983707,10.263267]]]}}'),
(8, 13, 12, 'San Ramon', '2025-07-16 04:56:27', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.968503,10.357618],[124.966809,10.357386],[124.964901,10.357872],[124.963465,10.357893],[124.963636,10.357006],[124.965051,10.356943],[124.966852,10.35631],[124.967967,10.356035],[124.968997,10.355634],[124.969426,10.356563],[124.969211,10.357112],[124.969018,10.35745],[124.968503,10.357618]]]}}'),
(9, 12, 12, 'San Agustin', '2025-07-16 14:21:34', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.974203,10.318614],[124.963603,10.316883],[124.961972,10.314054],[124.9612,10.311267],[124.961329,10.310127],[124.964547,10.309029],[124.968667,10.30962],[124.973903,10.310972],[124.975405,10.312069],[124.975533,10.313083],[124.974504,10.31494],[124.974418,10.31684],[124.974675,10.317938],[124.974203,10.318614]]]}}'),
(10, 12, 12, 'Banday', '2025-07-17 03:41:22', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.97902,10.309615],[124.97813,10.308887],[124.979031,10.308317],[124.978838,10.308137],[124.978838,10.307789],[124.978806,10.307662],[124.979482,10.306675],[124.980319,10.306126],[124.980791,10.306464],[124.981692,10.306865],[124.981992,10.307245],[124.981005,10.308871],[124.97902,10.309615]]]}}'),
(11, 14, 12, 'soro soro', '2025-07-17 04:23:14', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.840586,10.135901],[124.840693,10.134549],[124.840586,10.133726],[124.840565,10.132691],[124.840672,10.131402],[124.842045,10.131824],[124.842067,10.132458],[124.843032,10.132564],[124.843762,10.132712],[124.845049,10.132543],[124.845393,10.132754],[124.845328,10.133683],[124.845521,10.134064],[124.845285,10.134739],[124.844513,10.135077],[124.844041,10.135542],[124.843161,10.135817],[124.842153,10.135669],[124.841595,10.135817],[124.841273,10.135774],[124.840586,10.135901]]]}}'),
(12, 12, 12, 'Cabascan', '2025-07-17 14:05:47', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.963818,10.318255],[124.956179,10.313526],[124.956865,10.308713],[124.963388,10.304828],[124.966736,10.306939],[124.970598,10.309473],[124.970169,10.313273],[124.968195,10.317664],[124.963818,10.318255]]]}}');

-- --------------------------------------------------------

--
-- Table structure for table `docs`
--

CREATE TABLE `docs` (
  `id` int NOT NULL,
  `type` varchar(250) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` text,
  `store_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `household`
--

CREATE TABLE `household` (
  `id` int NOT NULL,
  `house_id` int NOT NULL,
  `family_name` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `suffix` varchar(250) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `occupation` varchar(250) DEFAULT NULL,
  `relationship` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `household`
--

INSERT INTO `household` (`id`, `house_id`, `family_name`, `name`, `suffix`, `birthdate`, `occupation`, `relationship`) VALUES
(7, 18, 'Alboleras', 'Joshua Alboleras', 'N/A', '2025-07-21', 'N/A', 'Children'),
(8, 18, 'Alboleras', 'Shine Montejo', 'N/A', '2025-07-21', 'N/A', 'Children'),
(9, 19, 'Catig', 'Mark Loquias', 'N/A', '2025-07-21', 'N/A', 'Children'),
(10, 19, 'Catig', 'Jake Lo', 'N/A', '2025-07-21', 'N/A', 'Children'),
(11, 19, 'Catig', 'Ray Loquias', '', '2025-07-21', 'N/A', 'Children'),
(12, 20, 'Montejo', 'Shine Montejo', 'N/A', '2025-07-21', 'N/A', 'Children'),
(13, 20, 'Montejo', 'Ana Montejo', 'N/A', '2025-07-21', 'N/A', 'Mother'),
(14, 20, 'Montejo', 'Charlie', 'jr', '2025-07-21', 'N/A', 'Children');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int NOT NULL,
  `house_number` int NOT NULL,
  `barangay_id` int NOT NULL,
  `province_id` int NOT NULL,
  `municipal_id` int NOT NULL,
  `building_type` varchar(250) DEFAULT NULL,
  `status` enum('occupied','vacant','under construction') DEFAULT NULL,
  `no_floors` int DEFAULT NULL,
  `year_built` date DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `geojson` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_number`, `barangay_id`, `province_id`, `municipal_id`, `building_type`, `status`, `no_floors`, `year_built`, `street_name`, `geojson`) VALUES
(18, 2210163, 4, 12, 12, 'Concrete', 'occupied', 1, '1990-07-11', 'San Miguel, Tomas Oppus, Southern Leyte', '{\"type\":\"Point\",\"coordinates\":[124.980896,10.285162]}'),
(19, 2210163, 4, 12, 12, 'Concrete', 'vacant', 1, '2025-07-21', 'San Miguel, Tomas Oppus, Southern Leyte', '{\"type\":\"Point\",\"coordinates\":[124.980897,10.285136]}'),
(20, 123123, 10, 12, 12, 'Concrete', 'vacant', 1, '2025-07-21', 'San Miguel, Tomas Oppus, Southern Leyte', '{\"type\":\"Point\",\"coordinates\":[124.980076,10.307455]}');

-- --------------------------------------------------------

--
-- Table structure for table `locator_slips`
--

CREATE TABLE `locator_slips` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `geojson` text NOT NULL,
  `purpose` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `locator_slips`
--

INSERT INTO `locator_slips` (`id`, `name`, `geojson`, `purpose`, `created_at`) VALUES
(21, 'Joshua Alboleras', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98288569989982,10.256206039964493]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98609524680688,10.254899558707844]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97617819503024,10.31037196895097]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98197257276034,10.305664138921552]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98210147705849,10.301315139263238]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98051486648677,10.292439616086707]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98094407965196,10.285556902341524]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98248985621927,10.277491691031202]},\"properties\":{\"type\":\"route_point\",\"order\":6}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98403502361401,10.267061505211167]},\"properties\":{\"type\":\"route_point\",\"order\":7}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98287614806794,10.26005155143103]},\"properties\":{\"type\":\"route_point\",\"order\":8}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98287614806794,10.256208679557242]},\"properties\":{\"type\":\"route_point\",\"order\":9}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98609524680688,10.254899558707844]},\"properties\":{\"type\":\"route_point\",\"order\":10}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98476467405023,10.24603117823325]},\"properties\":{\"type\":\"route_point\",\"order\":11}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[124.97617819503024,10.31037196895097],[124.98197257276034,10.305664138921552],[124.98210147705849,10.301315139263238],[124.98051486648677,10.292439616086707],[124.98094407965196,10.285556902341524],[124.98248985621927,10.277491691031202],[124.98403502361401,10.267061505211167],[124.98287614806794,10.26005155143103],[124.98287614806794,10.256208679557242],[124.98609524680688,10.254899558707844],[124.98476467405023,10.24603117823325]]},\"properties\":{\"type\":\"route_line\"}}]}', 'Mamalit ug paninda', '2025-07-20 11:00:46'),
(22, 'fsdafad', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87780841687226,10.290902630709006]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88819537546989,10.297151866291282]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89523447137904,10.29833414017651]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.90210188202208,10.30179648819635]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.90742412527047,10.308805515317466]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.91420569328046,10.31387218480057]},\"properties\":{\"type\":\"route_point\",\"order\":6}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[123.87780841687226,10.290902630709006],[123.88819537546989,10.297151866291282],[123.89523447137904,10.29833414017651],[123.90210188202208,10.30179648819635],[123.90742412527047,10.308805515317466],[123.91420569328046,10.31387218480057]]},\"properties\":{\"type\":\"route_line\"}}]}', 'dsafsdfa', '2025-07-20 15:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `id` int NOT NULL,
  `province_id` int NOT NULL,
  `municipality` varchar(250) DEFAULT NULL,
  `geojson` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`id`, `province_id`, `municipality`, `geojson`, `date_added`) VALUES
(12, 12, 'Tomas Oppus', '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[124.972912,10.326013],[124.967972,10.324672],[124.962074,10.31931],[124.96201,10.312344],[124.963854,10.30829],[124.965177,10.297861],[124.965864,10.29195],[124.964477,10.263573],[124.952613,10.229113],[124.961876,10.21999],[124.978687,10.222693],[124.979373,10.239249],[124.985548,10.252087],[124.980954,10.283336],[124.981298,10.304448],[124.98044,10.307995],[124.976151,10.311373],[124.976323,10.32083],[124.972912,10.326013]]]}}', '2025-07-16 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int NOT NULL,
  `province_name` varchar(250) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `province_name`, `date_added`) VALUES
(12, 'Southen Leyte', '2025-07-16 04:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(250) DEFAULT NULL,
  `redirect_to` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `redirect_to`) VALUES
(1, 'Super Admin', 'superadmin/index.php'),
(2, 'Municipal Official', 'municipalofficial/index.php\r\n'),
(3, 'Barangay Official', 'barangayofficial/index.php\r\n'),
(4, 'user', 'user/index.php'),
(5, 'store_owner', 'user/store_owner.php');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int NOT NULL,
  `province_id` int NOT NULL,
  `municipal_id` int NOT NULL,
  `barangay_id` int NOT NULL,
  `owner_name` varchar(250) DEFAULT NULL,
  `geojson` text,
  `owner_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `province_id`, `municipal_id`, `barangay_id`, `owner_name`, `geojson`, `owner_id`) VALUES
(6, 12, 12, 4, 'Shine Montejo', '{\"type\":\"Point\",\"coordinates\":[124.980906,10.285262]}', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`) VALUES
(6, 1, 'Joshua', 'albolerasjoshualuis@gmail.com', '$2y$10$g/mQ8tnMKxErw03IFn5.4OfQz00ZlbMtZDjwnW2sC42CUSyEaW5ee'),
(7, 2, 'Romwel', 'bayona@gmail.com', '$2y$10$ovOb3gn3udT8dGNzAyZVHeOQANPjnCEOBpaVmbtcb.kwFgk1c8/ga'),
(8, 3, 'Jay', 'maunes@gmail.com', '$2y$10$MiE0OBvocZOKzfEAiYHHpuhqehQ6OsZmPBUBfDxpVISVqNghXWbYO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `docs`
--
ALTER TABLE `docs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_store` (`store_id`);

--
-- Indexes for table `household`
--
ALTER TABLE `household`
  ADD PRIMARY KEY (`id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipal_id` (`municipal_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `locator_slips`
--
ALTER TABLE `locator_slips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`owner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `docs`
--
ALTER TABLE `docs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `household`
--
ALTER TABLE `household`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `locator_slips`
--
ALTER TABLE `locator_slips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `docs`
--
ALTER TABLE `docs`
  ADD CONSTRAINT `fk_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `household`
--
ALTER TABLE `household`
  ADD CONSTRAINT `household_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`);

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`municipal_id`) REFERENCES `municipalities` (`id`),
  ADD CONSTRAINT `houses_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `houses_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`);

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`owner_id`) REFERENCES `household` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
