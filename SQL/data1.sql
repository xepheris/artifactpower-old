-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Host: 
-- Erstellungszeit: 04. Okt 2016 um 15:34
-- Server Version: 5.5.50-0+deb7u2-log
-- PHP-Version: 5.4.45-0+deb7u5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: ``
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `data1`
--

CREATE TABLE IF NOT EXISTS `data1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` mediumint(15) NOT NULL,
  `percent` double NOT NULL,
  `alevel` smallint(2) NOT NULL,
  `ilvl` smallint(3) NOT NULL,
  `timestamp` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `char` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `server` varchar(22) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=156465 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
