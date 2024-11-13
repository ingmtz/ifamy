-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Feb 18, 2023 at 02:19 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nv_apics2`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `Id` int NOT NULL,
  `starttime` varchar(20) DEFAULT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `break` int DEFAULT NULL,
  `startbreak` varchar(20) DEFAULT NULL,
  `endbreak` varchar(20) DEFAULT NULL,
  `tagprefix` varchar(20) DEFAULT NULL,
  `itemprefix` varchar(20) DEFAULT NULL,
  `validationstep` int DEFAULT NULL,
  `fechaempaquemin` date DEFAULT NULL,
  `fechaempaquemax` date DEFAULT NULL,
  `fecharecibomin` date DEFAULT NULL,
  `fecharecibomax` date DEFAULT NULL,
  `fechacaducidadmin` date DEFAULT NULL,
  `fechacaducidadmax` date DEFAULT NULL,
  `btwtagfile` varchar(500) DEFAULT NULL,
  `tagfilesprintpath` varchar(500) DEFAULT NULL,
  `btwlocationfile` varchar(500) DEFAULT NULL,
  `locationfilesprintpath` varchar(500) DEFAULT NULL,
  `starttag` int DEFAULT NULL,
  `btwuserfile` varchar(500) DEFAULT NULL,
  `userfilesprintpath` varchar(500) DEFAULT NULL,
  `inventorytype` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`Id`, `starttime`, `endtime`, `break`, `startbreak`, `endbreak`, `tagprefix`, `itemprefix`, `validationstep`, `fechaempaquemin`, `fechaempaquemax`, `fecharecibomin`, `fecharecibomax`, `fechacaducidadmin`, `fechacaducidadmax`, `btwtagfile`, `tagfilesprintpath`, `btwlocationfile`, `locationfilesprintpath`, `starttag`, `btwuserfile`, `userfilesprintpath`, `inventorytype`) VALUES
(1, '11:00', '21:00', 1, '13:00', '14:00', 'MAT', 'P', 1, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_tag.btw', '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\', '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_location.btw', '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\', 17001, '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_user.btw', '\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\', 'SEATS');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `Id` bigint NOT NULL,
  `uid` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `lastsync` datetime DEFAULT NULL,
  `lastuser` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `Id` bigint NOT NULL,
  `plant` varchar(200) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `wh` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `plataforma` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`Id`, `plant`, `area`, `wh`, `location`, `plataforma`) VALUES
(1, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RB-C7-N3-I', 'PENDIENTE'),
(2, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C3-N1', 'PENDIENTE'),
(3, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C3-N2', 'PENDIENTE'),
(4, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C4-N1', 'PENDIENTE'),
(5, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C4-N2', 'PENDIENTE'),
(6, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C4-N3', 'PENDIENTE'),
(7, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C5-N1', 'PENDIENTE'),
(8, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C5-N2', 'PENDIENTE'),
(9, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C5-N3', 'PENDIENTE'),
(10, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RB-C3-N1-E', 'PENDIENTE'),
(11, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C3-N3-I', 'PENDIENTE'),
(12, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C3-N3-M', 'PENDIENTE'),
(13, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C3-N3-E', 'PENDIENTE'),
(14, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C7-N3-I', 'PENDIENTE'),
(15, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C8-N1', 'PENDIENTE'),
(16, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C8-N2', 'PENDIENTE'),
(17, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RC-C8-N3', 'PENDIENTE'),
(18, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RE-C4-N2-I', 'PENDIENTE'),
(19, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RB-C3-N3-I', 'PENDIENTE'),
(20, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C5-N2-M', 'PENDIENTE'),
(21, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C5-N3-M', 'PENDIENTE'),
(22, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C6-N3-M', 'PENDIENTE'),
(23, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C7-N2-M', 'PENDIENTE'),
(24, 'PENDIENTE', 'PENDIENTE', 'PENDIENTE', 'MAT-RD-C7-N2-I', 'PENDIENTE');

-- --------------------------------------------------------

--
-- Table structure for table `masterdata`
--

CREATE TABLE `masterdata` (
  `Id` bigint NOT NULL,
  `itemcode` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `item_group` varchar(200) DEFAULT NULL,
  `cost_price` double DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `masterdata`
--

INSERT INTO `masterdata` (`Id`, `itemcode`, `description`, `type`, `item_group`, `cost_price`, `uom`) VALUES
(1, '10174011QK', 'TYSON FRESH MEATS, INC', '21', NULL, 10, 'L'),
(2, '10120A00CK', 'GREATER OMAHA PACKING., INC', '', NULL, 20, 'L'),
(3, '10123A08SK', 'RICARNES BEEF', '', NULL, 50, ''),
(4, '40412000IK', 'TYSON FRESH MEATS, INC', '', NULL, 80, 'L'),
(5, '10123A00AK', 'TYSON FRESH MEATS, INC', '', NULL, 98, 'L'),
(6, '10168000AK', 'AB FOODS LLC', '', NULL, 116, 'L'),
(7, '10112A05LK', 'TYSON FRESH MEATS, INC', '', NULL, 134, 'L'),
(8, '40406A00IK', 'TYSON FRESH MEATS, INC', '', NULL, 152, 'L'),
(9, '10120A04BK', 'TYSON FRESH MEATS, INC', '21', NULL, 170, 'L'),
(10, '10184000DK', 'CARGILL MEAT SOLUTIONS CORPORATION', '', NULL, 242, ''),
(11, '10180A00RK', 'AB FOODS LLC', '', NULL, 260, 'L'),
(12, '10184D00CK', 'CARGILL MEAT SOLUTIONS CORPORATION', '', NULL, 314, ''),
(13, '10184030HK', 'TYSON FRESH MEATS, INC', '21', NULL, 332, 'L'),
(14, '10184014QK', 'GREATER OMAHA PACKING., INC', '', NULL, 386, 'L'),
(15, '40413000IK', 'TYSON FRESH MEATS, INC', '', NULL, 404, 'L'),
(16, '40406A00IP', 'TYSON FRESH MEATS, INC', '', NULL, 422, 'L');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `Id` int NOT NULL,
  `route` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`Id`, `route`) VALUES
(1, 'capture/*'),
(2, 'users/*'),
(3, 'itemcodes/*'),
(4, 'reports/*'),
(5, 'tags/*'),
(6, 'areas/*'),
(7, 'changepassword/*'),
(8, 'exportfile/*'),
(9, 'scannermanagement/*'),
(10, 'databaseconfig/*'),
(11, 'settings/*');

-- --------------------------------------------------------

--
-- Table structure for table `permissionsbyprofile`
--

CREATE TABLE `permissionsbyprofile` (
  `Id` int NOT NULL,
  `idprofile` int DEFAULT NULL,
  `idpermission` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissionsbyprofile`
--

INSERT INTO `permissionsbyprofile` (`Id`, `idprofile`, `idpermission`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 3, 2),
(7, 3, 3),
(8, 3, 4),
(9, 3, 5),
(10, 3, 6),
(11, 1, 7),
(12, 2, 7),
(13, 3, 7),
(14, 3, 8),
(15, 3, 9),
(16, 3, 10),
(17, 3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `perpetuo`
--

CREATE TABLE `perpetuo` (
  `Id` bigint NOT NULL,
  `itemcode` varchar(200) DEFAULT NULL,
  `onhand` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perpetuo`
--

INSERT INTO `perpetuo` (`Id`, `itemcode`, `onhand`) VALUES
(1, '10174011QK', 1),
(2, '10120A00CK', 5),
(3, '10123A08SK', 1),
(4, '40412000IK', 1),
(5, '10123A00AK', 3),
(6, '10168000AK', 1),
(7, '10112A05LK', 1),
(8, '40406A00IK', 1),
(9, '10120A04BK', 1),
(10, '10184000DK', 2),
(11, '10180A00RK', 2),
(12, '10184D00CK', 1),
(13, '10184030HK', 1),
(14, '10184014QK', 1),
(15, '40413000IK', 1),
(16, '40406A00IP', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `Id` bigint NOT NULL,
  `profile` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`Id`, `profile`) VALUES
(1, 'Analyst'),
(2, 'Data Capture'),
(3, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `scanners`
--

CREATE TABLE `scanners` (
  `Id` bigint NOT NULL,
  `serial` varchar(250) DEFAULT NULL,
  `brand` varchar(250) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `assigneduser` int DEFAULT NULL,
  `assignator` int DEFAULT NULL,
  `assignmenttime` datetime DEFAULT NULL,
  `unassignator` int DEFAULT NULL,
  `unassignmenttime` datetime DEFAULT NULL,
  `creationdate` datetime DEFAULT NULL,
  `creationuser` int DEFAULT NULL,
  `warehouse` varchar(250) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `area` varchar(250) DEFAULT NULL,
  `assignmentstatus` int DEFAULT NULL,
  `active` int DEFAULT NULL,
  `plant` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scannersrecords`
--

CREATE TABLE `scannersrecords` (
  `Id` bigint NOT NULL,
  `assignmentstatus` int DEFAULT NULL,
  `assigneduser` int DEFAULT NULL,
  `assignator` int DEFAULT NULL,
  `assignmenttime` datetime DEFAULT NULL,
  `unassignator` int DEFAULT NULL,
  `unassignmenttime` datetime DEFAULT NULL,
  `idscanner` int DEFAULT NULL,
  `plant` int DEFAULT NULL,
  `area` int DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `Id` bigint NOT NULL,
  `tag` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `plant` varchar(200) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `warehouse` varchar(50) DEFAULT NULL,
  `datetimecaptured` datetime DEFAULT NULL,
  `itemcode` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `reasontoedit` varchar(60) DEFAULT NULL,
  `idcounter` int DEFAULT NULL,
  `idauditor` int DEFAULT NULL,
  `printcounter` int DEFAULT NULL,
  `toaudit` int DEFAULT NULL,
  `resultaudit` varchar(50) DEFAULT NULL,
  `iduseraudit` int DEFAULT NULL,
  `datetimeaudit` datetime DEFAULT NULL,
  `openbox` varchar(10) DEFAULT NULL,
  `auditcounter` int DEFAULT NULL,
  `precounted` varchar(10) DEFAULT NULL,
  `fechaempaque` date DEFAULT NULL,
  `fecharecibo` date DEFAULT NULL,
  `fechacaducidad` date DEFAULT NULL,
  `lot` varchar(200) DEFAULT NULL,
  `packingdate` varchar(200) DEFAULT NULL,
  `uom` varchar(5) DEFAULT NULL,
  `imported` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`Id`, `tag`, `plant`, `area`, `warehouse`, `datetimecaptured`, `itemcode`, `status`, `qty`, `location`, `iduser`, `reasontoedit`, `idcounter`, `idauditor`, `printcounter`, `toaudit`, `resultaudit`, `iduseraudit`, `datetimeaudit`, `openbox`, `auditcounter`, `precounted`, `fechaempaque`, `fecharecibo`, `fechacaducidad`, `lot`, `packingdate`, `uom`, `imported`) VALUES
(1, '0190027182085093320100072011221206210043', NULL, NULL, NULL, NULL, '10174011QK', NULL, 30.07, 'MAT-RB-C7-N3-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-06', 'L', 1),
(2, '0196303080101207320100072511221230211002', NULL, NULL, NULL, '2023-02-17 21:34:33', '10120A00CK', 'Used', 32.89, 'MAT-RC-C3-N1', 1, '', NULL, NULL, NULL, NULL, '', 0, '0000-00-00 00:00:00', '', 0, NULL, NULL, NULL, NULL, '', '2022-12-30', 'L', 1),
(3, 'DESH00000269029501', NULL, NULL, NULL, NULL, '10123A08SK', NULL, 16.28, 'MAT-RC-C3-N2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 1),
(4, '0190027182001291320100069311221125210261', NULL, NULL, NULL, NULL, '10120A00CK', NULL, 31.43, 'MAT-RC-C4-N1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-25', 'L', 1),
(5, '0190027182101410320100086211221203210389', NULL, NULL, NULL, NULL, '40412000IK', NULL, 39.1, 'MAT-RC-C4-N2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-03', 'L', 1),
(6, '0190027182071379320100063511230109210115', NULL, NULL, NULL, NULL, '10123A00AK', NULL, 28.8, 'MAT-RC-C4-N3', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-09', 'L', 1),
(7, '0190038079510239320100072811221226214950', NULL, NULL, NULL, NULL, '10168000AK', NULL, 33, 'MAT-RC-C5-N1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-26', 'L', 1),
(8, '0190027182007187320100091711220921210117', NULL, NULL, NULL, NULL, '10112A05LK', NULL, 41.59, 'MAT-RC-C5-N2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-21', 'L', 1),
(9, '0190027182106484320100070811221216210389', NULL, NULL, NULL, NULL, '40406A00IK', NULL, 32.11, 'MAT-RC-C5-N3', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-16', 'L', 1),
(10, '0190027182085192320100058111230104210044', NULL, NULL, NULL, NULL, '10120A04BK', NULL, 26.35, 'MAT-RB-C3-N1-E', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04', 'L', 1),
(11, '0190027182001291320100069011221125210261', NULL, NULL, NULL, NULL, '10120A00CK', NULL, 31.29, 'MAT-RD-C3-N3-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-25', 'L', 1),
(12, '0190027182001291320100069911221125210261', NULL, NULL, NULL, NULL, '10120A00CK', NULL, 31.7, 'MAT-RD-C3-N3-M', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-25', 'L', 1),
(13, '0190027182001291320100069011221125210261', NULL, NULL, NULL, NULL, '10120A00CK', NULL, 30.93, 'MAT-RD-C3-N3-E', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-25', 'L', 1),
(14, '0190028749184440112301073202007190212636', NULL, NULL, NULL, NULL, '10184000DK', NULL, 32.61, 'MAT-RD-C7-N3-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 1),
(15, '0190038079602217320100065311221108214899', NULL, NULL, NULL, NULL, '10180A00RK', NULL, 29.6, 'MAT-RC-C8-N1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-08', 'L', 1),
(16, '0190028749184440112301123202007250212652', NULL, NULL, NULL, NULL, '10184000DK', NULL, 32.89, 'MAT-RC-C8-N2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 1),
(17, '0190038079143260320100073111221109214898', NULL, NULL, NULL, NULL, '10180A00RK', NULL, 31.8, 'MAT-RC-C8-N3', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-09', 'L', 1),
(18, '0190028749056143112212303202004240214631', NULL, NULL, NULL, NULL, '10184D00CK', NULL, 19.23, 'MAT-RE-C4-N2-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 1),
(19, '0190027182015847320100066611221221210043', NULL, NULL, NULL, NULL, '10184030HK', NULL, 30.21, 'MAT-RB-C3-N3-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21', 'L', 1),
(20, '0190027182071379320100069611230112210118', NULL, NULL, NULL, NULL, '10123A00AK', NULL, 31.57, 'MAT-RD-C5-N2-M', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-12', 'L', 1),
(21, '0190027182071379320100071811230109210115', NULL, NULL, NULL, NULL, '10123A00AK', NULL, 32.56, 'MAT-RD-C5-N3-M', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-09', 'L', 1),
(22, '0196303080541843320100074011230104212051', NULL, NULL, NULL, NULL, '10184014QK', NULL, 3.57, 'MAT-RD-C6-N3-M', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04', 'L', 1),
(23, '0190027182139130320100041411230113210386', NULL, NULL, NULL, NULL, '40413000IK', NULL, 18.77, 'MAT-RD-C7-N2-M', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-13', 'L', 1),
(24, '0190027182106484320100068311221216210389', NULL, NULL, NULL, NULL, '40406A00IP', NULL, 30.98, 'MAT-RD-C7-N2-I', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-16', 'L', 1),
(25, '0196303080101207320100072511221230211042', NULL, NULL, NULL, '2023-02-17 21:34:46', '', 'Used', 7.25, '', 1, '', NULL, NULL, NULL, NULL, '', 0, '0000-00-00 00:00:00', '', 0, NULL, NULL, NULL, NULL, '', '2022-12-30', 'L', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` bigint NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `idprofile` int DEFAULT NULL,
  `active` int DEFAULT NULL,
  `creationdate` datetime DEFAULT NULL,
  `changepassword` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `name`, `username`, `password`, `idprofile`, `active`, `creationdate`, `changepassword`) VALUES
(1, 'Administrator', 'admin', 'admin', 3, 1, '2020-02-24 12:15:17', 0),
(2, 'Hand Held 1', 'hand1', 'hand1', 2, 1, '2021-11-26 11:43:45', 1),
(3, 'Hand Held 2', 'hand2', 'hand2', 2, 1, '2021-11-26 11:43:57', 1),
(4, 'Hand Held 3', 'hand3', 'hand3', 2, 1, '2021-11-26 11:44:11', 1),
(5, 'Hand Held 4', 'hand4', 'hand4', 2, 1, '2021-11-26 11:44:24', 1),
(6, 'Hand Held 5', 'hand5', 'hand5', 2, 1, '2021-11-26 11:44:42', 1),
(7, 'Auditor1', 'aud1', 'aud1', 1, 1, '2021-11-26 11:44:54', 1),
(8, 'Auditor2', 'aud2', 'aud2', 1, 1, '2021-11-26 11:45:08', 1),
(9, 'Auditor3', 'aud3', 'aud3', 1, 1, '2021-11-26 11:45:23', 1),
(10, 'Juan', 'juan', 'juan', 1, 0, '2021-11-26 11:45:37', 1),
(11, 'Auditor4', 'aud4', 'aud4', 1, 1, '2022-02-18 18:31:49', 1),
(12, 'Auditor5', 'aud5', 'aud5', 1, 1, '2022-02-18 18:32:05', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `masterdata`
--
ALTER TABLE `masterdata`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `itemcode` (`itemcode`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `permissionsbyprofile`
--
ALTER TABLE `permissionsbyprofile`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `perpetuo`
--
ALTER TABLE `perpetuo`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id` (`Id`),
  ADD KEY `Id_2` (`Id`),
  ADD KEY `Id_3` (`Id`),
  ADD KEY `itemcode` (`itemcode`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `scanners`
--
ALTER TABLE `scanners`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `scannersrecords`
--
ALTER TABLE `scannersrecords`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `itemcode` (`itemcode`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `masterdata`
--
ALTER TABLE `masterdata`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissionsbyprofile`
--
ALTER TABLE `permissionsbyprofile`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `perpetuo`
--
ALTER TABLE `perpetuo`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `scanners`
--
ALTER TABLE `scanners`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scannersrecords`
--
ALTER TABLE `scannersrecords`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
