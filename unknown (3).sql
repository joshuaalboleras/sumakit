-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2025 at 04:13 PM
-- Server version: 8.0.30
-- PHP Version: 8.0.30

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
  `store_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `household`
--

CREATE TABLE `household` (
  `id` int NOT NULL,
  `house_id` int DEFAULT NULL,
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
  `barangay_id` int DEFAULT NULL,
  `province_id` int DEFAULT NULL,
  `municipal_id` int DEFAULT NULL,
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
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `description`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Nescafe classic ', 'pero nag rg c romwel haha', '15.00', 10, '2025-07-21 21:26:12', '2025-07-22 06:33:50'),
(2, 'kopiko blanko', 'isang higop mo patay c jay haha', '15.00', 9, '2025-07-21 21:31:51', '2025-07-22 07:03:22'),
(12, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:20', '2025-07-22 05:52:20'),
(13, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:20', '2025-07-22 05:52:20'),
(14, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:20', '2025-07-22 05:52:20'),
(15, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:20', '2025-07-22 05:52:20'),
(16, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:21', '2025-07-22 05:52:21'),
(17, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:21', '2025-07-22 05:52:21'),
(18, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:21', '2025-07-22 05:52:21'),
(19, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:21', '2025-07-22 05:52:21'),
(20, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:23', '2025-07-22 05:52:23'),
(21, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:23', '2025-07-22 05:52:23'),
(22, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:24', '2025-07-22 05:52:24'),
(23, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:25', '2025-07-22 05:52:25'),
(24, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:25', '2025-07-22 05:52:25'),
(25, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:25', '2025-07-22 05:52:25'),
(26, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:26', '2025-07-22 05:52:26'),
(27, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:26', '2025-07-22 05:52:26'),
(28, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:26', '2025-07-22 05:52:26'),
(29, 'shampoo', 'shine hair', '12.00', 100, '2025-07-22 05:52:27', '2025-07-22 05:52:27'),
(61, 'Pancit Canton', 'favorite ni jay', '10.00', 4, '2025-07-22 06:23:31', '2025-07-22 07:03:53'),
(62, 'SafeGuard', 'paras hugaw ni jay', '15.00', 5, '2025-07-22 06:24:26', '2025-07-22 06:24:26'),
(63, 'sabon ni yohan makagwapo', 'maka-gwapo', '10.00', 10, '2025-07-22 06:25:32', '2025-07-22 06:25:32'),
(64, 'asin', 'para sa baba ni jay', '8.00', 10, '2025-07-22 06:30:57', '2025-07-22 06:30:57'),
(65, 'lotion', 'para kang jay', '20.00', 9, '2025-07-22 06:48:52', '2025-07-22 07:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `store_id` int NOT NULL,
  `transaction_type` enum('sale','purchase','adjustment','return') NOT NULL,
  `quantity` int NOT NULL,
  `reference_id` int DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `notes` text,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(22, 'fsdafad', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87780841687226,10.290902630709006]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88819537546989,10.297151866291282]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89523447137904,10.29833414017651]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.90210188202208,10.30179648819635]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.90742412527047,10.308805515317466]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.91420569328046,10.31387218480057]},\"properties\":{\"type\":\"route_point\",\"order\":6}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[123.87780841687226,10.290902630709006],[123.88819537546989,10.297151866291282],[123.89523447137904,10.29833414017651],[123.90210188202208,10.30179648819635],[123.90742412527047,10.308805515317466],[123.91420569328046,10.31387218480057]]},\"properties\":{\"type\":\"route_line\"}}]}', 'dsafsdfa', '2025-07-20 15:11:45'),
(23, '12345', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.9754119868674,9.843038628071653]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.91069680408945,10.251355052258905]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.94811866932268,10.24792030501917]},\"properties\":{\"type\":\"checkpoint\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.9038489063946,10.275509844629017]},\"properties\":{\"type\":\"checkpoint\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88324372683648,10.302533994481868]},\"properties\":{\"type\":\"checkpoint\",\"order\":5}}]}', 'walking', '2025-07-21 18:25:00'),
(24, 'hello', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87095077534235,10.289669658283369]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87017808110889,10.30098579496852]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88777833864813,10.296932300137726]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88691978949988,10.30723483071269]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89327305319698,10.30892373801873]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[123.87095077534235,10.289669658283369],[123.87017808110889,10.30098579496852],[123.88777833864813,10.296932300137726],[123.88691978949988,10.30723483071269],[123.89327305319698,10.30892373801873]]},\"properties\":{\"type\":\"route_line\"}}]}', 'world', '2025-07-21 19:26:22'),
(25, 'hfhgf', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98225155015476,10.3018584189469]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.99028697775323,10.175951003559662]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98118635678173,10.199097836781233]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.975726576628,10.2349692886344]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.98225155015476,10.30287178170636]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[124.99028697775323,10.175951003559662],[124.98118635678173,10.199097836781233],[124.975726576628,10.2349692886344],[124.98225155015476,10.30287178170636]]},\"properties\":{\"type\":\"route_line\"}}]}', 'ff', '2025-07-21 19:34:09'),
(26, 'yohanbayot', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88684314059765,10.307769653198266]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.8786010687744,10.296960450446106]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87053070678081,10.289472607961178]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.8698438674622,10.30078875171548]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88787339957554,10.29656635749734]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88512604230114,10.314806694847617]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89972137782146,10.318015533824967]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[123.87053070678081,10.289472607961178],[123.8698438674622,10.30078875171548],[123.88787339957554,10.29656635749734],[123.88512604230114,10.314806694847617],[123.89972137782146,10.318015533824967]]},\"properties\":{\"type\":\"route_line\"}}]}', 'walking', '2025-07-21 19:54:02'),
(27, 'macaldo', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.96374350300492,10.342729885100844]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97003280250529,10.35589987525048]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.94641695747991,10.34579595471026]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.95053799339155,10.334819182053872]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.95457317438834,10.338787751834749]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.9615274224892,10.338281128697089]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.96530503874153,10.34579595471026]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97105731803481,10.34816013243366]},\"properties\":{\"type\":\"route_point\",\"order\":6}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97107398110175,10.354496181510473]},\"properties\":{\"type\":\"route_point\",\"order\":7}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97027982313959,10.354179557993819]},\"properties\":{\"type\":\"route_point\",\"order\":8}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[124.94641695747991,10.34579595471026],[124.95053799339155,10.334819182053872],[124.95457317438834,10.338787751834749],[124.9615274224892,10.338281128697089],[124.96530503874153,10.34579595471026],[124.97105731803481,10.34816013243366],[124.97107398110175,10.354496181510473],[124.97027982313959,10.354179557993819]]},\"properties\":{\"type\":\"route_line\"}}]}', 'wlobuton c jay', '2025-07-21 19:59:16'),
(28, 'afsaf', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87739909996685,10.288965905463233]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88821681923486,10.29572187186937]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89697402054703,10.298593113778395]},\"properties\":{\"type\":\"checkpoint\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88340894400463,10.280858554891571]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.87739909996685,10.289472607961178]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.88838852906449,10.29724194437166]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.89697402054703,10.298593113778395]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.9065897710075,10.29285060382279]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[123.91860945908307,10.307206682610381]},\"properties\":{\"type\":\"route_point\",\"order\":6}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[123.88340894400463,10.280858554891571],[123.87739909996685,10.289472607961178],[123.88838852906449,10.29724194437166],[123.89697402054703,10.298593113778395],[123.9065897710075,10.29285060382279],[123.91860945908307,10.307206682610381]]},\"properties\":{\"type\":\"route_line\"}}]}', 'na malinki', '2025-07-21 22:31:23'),
(29, '920292', '{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.9705305504053,10.355256076646063]},\"properties\":{\"type\":\"checkpoint\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97115299853782,10.354981670281752]},\"properties\":{\"type\":\"checkpoint\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.96951102329177,10.356712537175094]},\"properties\":{\"type\":\"route_point\",\"order\":1}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.96987590667976,10.35603707806087]},\"properties\":{\"type\":\"route_point\",\"order\":2}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97047689108356,10.355277184817993]},\"properties\":{\"type\":\"route_point\",\"order\":3}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97109933921604,10.355055548941912]},\"properties\":{\"type\":\"route_point\",\"order\":4}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[124.97162520056933,10.354369532142169]},\"properties\":{\"type\":\"route_point\",\"order\":5}},{\"type\":\"Feature\",\"geometry\":{\"type\":\"LineString\",\"coordinates\":[[124.96951102329177,10.356712537175094],[124.96987590667976,10.35603707806087],[124.97047689108356,10.355277184817993],[124.97109933921604,10.355055548941912],[124.97162520056933,10.354369532142169]]},\"properties\":{\"type\":\"route_line\"}}]}', 'walking', '2025-07-21 22:40:28');

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `id` int NOT NULL,
  `province_id` int DEFAULT NULL,
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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_items` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `total_items`, `total_amount`, `date_created`) VALUES
(1, '', 0, '100.00', '2025-07-21 23:36:04'),
(2, '', 0, '200.00', '2025-07-21 23:38:27'),
(3, 'jay', 0, '300.00', '2025-07-21 23:43:50'),
(4, 'Online Order', 0, '144667.00', '2025-07-22 00:02:09'),
(5, 'john', 0, '144544.00', '2025-07-22 00:05:03'),
(6, 'yohan', 0, '13313.00', '2025-07-22 00:18:53'),
(7, 'kris', 9, '433632.00', '2025-07-22 04:34:14'),
(8, 'laki', 2, '13313.00', '2025-07-22 05:31:49'),
(21, 'jay', 1, '20.00', '2025-07-22 07:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 4, 2, 1, '13213.00'),
(6, 4, 1, 1, '123.00'),
(8, 5, 2, 1, '13213.00'),
(12, 6, 2, 1, '13213.00'),
(14, 7, 2, 3, '13213.00'),
(17, 8, 2, 1, '13213.00'),
(40, 21, 65, 1, '20.00');

-- --------------------------------------------------------

--
-- Table structure for table `pos_accounts`
--

CREATE TABLE `pos_accounts` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pos_accounts`
--

INSERT INTO `pos_accounts` (`id`, `username`, `email`, `owner_name`, `password`, `created_at`, `updated_at`) VALUES
(1, 'shinemontejo436', 'amolatouser@gmail.com', 'Shine Montejo', '$2y$10$YMUnpn1CYqVa1vKOfhZrlux76XYZ8c4x17i8xzKv50MxCbihmjnVC', '2025-07-21 15:36:20', '2025-07-21 15:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `pos_accounts_backup`
--

CREATE TABLE `pos_accounts_backup` (
  `id` int NOT NULL,
  `store_id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_by` int NOT NULL COMMENT 'User ID of the store owner who created this account',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `store_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `sku` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost_price` decimal(10,2) DEFAULT '0.00',
  `unit` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pcs',
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('active','inactive','discontinued') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reorder_level` int DEFAULT '10',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `store_id`, `name`, `description`, `sku`, `barcode`, `category`, `price`, `cost_price`, `unit`, `taxable`, `status`, `image_url`, `reorder_level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nescafe classic ', 'pero nag rg c romwel haha', NULL, NULL, 'Food & Beverage', '15.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-21 21:26:12', '2025-07-22 06:33:50'),
(2, 1, 'kopiko blanko', 'isang higop mo patay c jay haha', NULL, NULL, 'Books & Media', '15.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-21 21:31:51', '2025-07-22 06:32:52'),
(61, 1, 'Pancit Canton', 'favorite ni jay', NULL, NULL, 'Food & Beverage', '10.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-22 06:23:31', '2025-07-22 06:23:31'),
(62, 1, 'SafeGuard', 'paras hugaw ni jay', NULL, NULL, 'Antibacteria', '15.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-22 06:24:26', '2025-07-22 06:24:26'),
(63, 1, 'sabon ni yohan makagwapo', 'maka-gwapo', NULL, NULL, 'Health & Beauty', '10.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-22 06:25:32', '2025-07-22 06:25:32'),
(64, 1, 'asin', 'para sa baba ni jay', NULL, NULL, 'Food & Beverage', '8.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-22 06:30:57', '2025-07-22 06:30:57'),
(65, 1, 'lotion', 'para kang jay', NULL, NULL, 'Health & Beauty', '20.00', '0.00', 'pcs', 1, 'active', NULL, 10, '2025-07-22 06:48:52', '2025-07-22 06:48:52');

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
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `store_id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','gcash','paymaya') DEFAULT 'cash',
  `payment_status` enum('pending','paid','partially_paid','cancelled') DEFAULT 'pending',
  `status` enum('completed','refunded','voided') DEFAULT 'completed',
  `cashier_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int NOT NULL,
  `sale_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `tax` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int NOT NULL,
  `province_id` int DEFAULT NULL,
  `municipal_id` int DEFAULT NULL,
  `barangay_id` int DEFAULT NULL,
  `owner_name` varchar(250) DEFAULT NULL,
  `geojson` text,
  `owner_id` int DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `province_id`, `municipal_id`, `barangay_id`, `owner_name`, `geojson`, `owner_id`, `username`, `password`) VALUES
(8, 12, 12, 4, 'Shine Montejo', '{\"type\":\"Point\",\"coordinates\":[124.980715,10.286105]}', NULL, 'shinemontejo', '$2y$10$lc/1bmC3RYZqR4CG4I5HDOYjMEE1L2MS0Z5wtp9iWHt17NoYHh0pe'),
(9, 12, 12, 4, 'Joshua Alboleras', '{\"type\":\"Point\",\"coordinates\":[124.979889,10.284011]}', 7, 'rayyy', '$2y$10$ukWEJpBg37/FTNGgHwdz/eHVD9HktCJKMT.zFrNjYWSwJBrbYHVKi'),
(10, 12, 12, 9, 'macaldo', '{\"type\":\"Point\",\"coordinates\":[124.971218,10.313315]}', NULL, 'yohan', '$2y$10$1C/kHD8M8VITXto.nNlHsOM0M.MpFW3lDj4jMlxFQlWFmTN.wOvQi'),
(11, 12, 12, 12, 'sdfafsad', '{\"type\":\"Point\",\"coordinates\":[124.962621,10.310412]}', NULL, 'fdsfa', '$2y$10$HlbkalF7nJCdV5Q4qtkNN.LUzeOvTELKOPkC29mEA../DHr31onQ6'),
(12, 12, 12, 4, 'ikot', '{\"type\":\"Point\",\"coordinates\":[124.979717,10.284603]}', NULL, 'ikot@gmail.com', '$2y$10$dKdfQCufgF3Zkm8m9tL0m.6a19AMHMrzMsd.iGmPM8ueyTMaDNJoa');

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
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `reference` (`reference_type`,`reference_id`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pos_accounts`
--
ALTER TABLE `pos_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pos_accounts_backup`
--
ALTER TABLE `pos_accounts_backup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `category` (`category`),
  ADD KEY `status` (`status`);

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
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `cashier_id` (`cashier_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`owner_id`),
  ADD KEY `stores_ibfk_1` (`province_id`),
  ADD KEY `stores_ibfk_2` (`municipal_id`),
  ADD KEY `stores_ibfk_3` (`barangay_id`);

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
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locator_slips`
--
ALTER TABLE `locator_slips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `pos_accounts`
--
ALTER TABLE `pos_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pos_accounts_backup`
--
ALTER TABLE `pos_accounts_backup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `fk_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `household`
--
ALTER TABLE `household`
  ADD CONSTRAINT `household_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`municipal_id`) REFERENCES `municipalities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `houses_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `houses_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`owner_id`) REFERENCES `household` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stores_ibfk_2` FOREIGN KEY (`municipal_id`) REFERENCES `municipalities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stores_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
