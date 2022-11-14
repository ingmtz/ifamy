-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-08-2021 a las 17:29:50
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ifa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `starttime` varchar(20) DEFAULT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `break` int(11) DEFAULT NULL,
  `startbreak` varchar(20) DEFAULT NULL,
  `endbreak` varchar(20) DEFAULT NULL,
  `tagprefix` varchar(20) DEFAULT NULL,
  `itemprefix` varchar(20) DEFAULT NULL,
  `validationstep` int(11) DEFAULT NULL,
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
  `starttag` int(11) DEFAULT NULL,
  `btwuserfile` varchar(500) DEFAULT NULL,
  `userfilesprintpath` varchar(500) DEFAULT NULL,
  `inventorytype` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`Id`, `starttime`, `endtime`, `break`, `startbreak`, `endbreak`, `tagprefix`, `itemprefix`, `validationstep`, `fechaempaquemin`, `fechaempaquemax`, `fecharecibomin`, `fecharecibomax`, `fechacaducidadmin`, `fechacaducidadmax`, `btwtagfile`, `tagfilesprintpath`, `btwlocationfile`, `locationfilesprintpath`, `starttag`, `btwuserfile`, `userfilesprintpath`, `inventorytype`) VALUES
(1, '07:00', '19:00', 1, '13:00', '14:00', 'F', 'P', 1, NULL, NULL, NULL, NULL, NULL, NULL, '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_tag.btw', '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\', '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_location.btw', '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\', 120000, '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_user.btw', '\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\', 'TRIM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `lastsync` datetime DEFAULT NULL,
  `lastuser` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plant` varchar(200) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `wh` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `plataforma` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masterdata`
--

DROP TABLE IF EXISTS `masterdata`;
CREATE TABLE IF NOT EXISTS `masterdata` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `itemcode` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `item_group` varchar(200) DEFAULT NULL,
  `cost_price` double DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `itemcode` (`itemcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permissions`
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
-- Estructura de tabla para la tabla `permissionsbyprofile`
--

DROP TABLE IF EXISTS `permissionsbyprofile`;
CREATE TABLE IF NOT EXISTS `permissionsbyprofile` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idprofile` int(11) DEFAULT NULL,
  `idpermission` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permissionsbyprofile`
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
-- Estructura de tabla para la tabla `perpetuo`
--

DROP TABLE IF EXISTS `perpetuo`;
CREATE TABLE IF NOT EXISTS `perpetuo` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `itemcode` varchar(200) DEFAULT NULL,
  `onhand` double DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id` (`Id`),
  KEY `Id_2` (`Id`),
  KEY `Id_3` (`Id`),
  KEY `itemcode` (`itemcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `profile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `profiles`
--

INSERT INTO `profiles` (`Id`, `profile`) VALUES
(1, 'Analyst'),
(2, 'Data Capture'),
(3, 'Administrator');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scanners`
--

DROP TABLE IF EXISTS `scanners`;
CREATE TABLE IF NOT EXISTS `scanners` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serial` varchar(250) DEFAULT NULL,
  `brand` varchar(250) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `assigneduser` int(11) DEFAULT NULL,
  `assignator` int(11) DEFAULT NULL,
  `assignmenttime` datetime DEFAULT NULL,
  `unassignator` int(11) DEFAULT NULL,
  `unassignmenttime` datetime DEFAULT NULL,
  `creationdate` datetime DEFAULT NULL,
  `creationuser` int(11) DEFAULT NULL,
  `warehouse` varchar(250) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `area` varchar(250) DEFAULT NULL,
  `assignmentstatus` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `plant` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scannersrecords`
--

DROP TABLE IF EXISTS `scannersrecords`;
CREATE TABLE IF NOT EXISTS `scannersrecords` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `assignmentstatus` int(11) DEFAULT NULL,
  `assigneduser` int(11) DEFAULT NULL,
  `assignator` int(11) DEFAULT NULL,
  `assignmenttime` datetime DEFAULT NULL,
  `unassignator` int(11) DEFAULT NULL,
  `unassignmenttime` datetime DEFAULT NULL,
  `idscanner` int(11) DEFAULT NULL,
  `plant` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) DEFAULT NULL,
  `plant` varchar(200) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `warehouse` varchar(50) DEFAULT NULL,
  `datetimecaptured` datetime DEFAULT NULL,
  `itemcode` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `reasontoedit` varchar(60) DEFAULT NULL,
  `idcounter` int(11) DEFAULT NULL,
  `idauditor` int(11) DEFAULT NULL,
  `printcounter` int(11) DEFAULT NULL,
  `toaudit` int(11) DEFAULT NULL,
  `resultaudit` varchar(50) DEFAULT NULL,
  `iduseraudit` int(11) DEFAULT NULL,
  `datetimeaudit` datetime DEFAULT NULL,
  `openbox` varchar(10) DEFAULT NULL,
  `auditcounter` int(11) DEFAULT NULL,
  `precounted` varchar(10) DEFAULT NULL,
  `fechaempaque` date DEFAULT NULL,
  `fecharecibo` date DEFAULT NULL,
  `fechacaducidad` date DEFAULT NULL,
  `lot` varchar(100) DEFAULT NULL,
  `uom` varchar(100) DEFAULT NULL,
  `packingdate` date DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `itemcode` (`itemcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `idprofile` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `creationdate` datetime DEFAULT NULL,
  `changepassword` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`Id`, `name`, `username`, `password`, `idprofile`, `active`, `creationdate`, `changepassword`) VALUES
(1, 'Administrator', 'admin', 'admin', 3, 1, '2020-02-24 12:15:17', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
