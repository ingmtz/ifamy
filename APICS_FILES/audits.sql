-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2024 at 10:16 PM
-- Server version: 8.3.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ifa`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
CREATE TABLE IF NOT EXISTS `audits` (
  `Id` bigint NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) DEFAULT NULL,
  `itemcode` varchar(50) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `reasontoedit` varchar(60) DEFAULT NULL,
  `resultaudit` varchar(50) DEFAULT NULL,
  `iduseraudit` int DEFAULT NULL,
  `datetimeaudit` datetime DEFAULT NULL,
  `openbox` varchar(10) DEFAULT NULL,
  `auditcounter` int DEFAULT NULL,
  `uom` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `itemcode` (`itemcode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
