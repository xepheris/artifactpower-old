-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Host: 
-- Erstellungszeit: 04. Okt 2016 um 15:33
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
-- Tabellenstruktur für Tabelle `pie_chart_data`
--

CREATE TABLE IF NOT EXISTS `pie_chart_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` smallint(3) NOT NULL,
  `users` varchar(10) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `pie_chart_data`
--

INSERT INTO `pie_chart_data` (`id`, `class`, `users`) VALUES
(1, 1, '14221'),
(2, 2, '15573'),
(3, 3, '14885'),
(4, 4, '9423'),
(5, 5, '11065'),
(6, 6, '11191'),
(7, 7, '13498'),
(8, 8, '12349'),
(9, 9, '7643'),
(10, 10, '7510'),
(11, 11, '19053'),
(12, 12, '18728'),
(13, 100, '1475586919');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
