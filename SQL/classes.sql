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
-- Tabellenstruktur für Tabelle `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `class` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `class_short` text COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `colorhex` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `classes`
--

INSERT INTO `classes` (`id`, `class`, `class_short`, `color`, `colorhex`) VALUES
(1, 'Warrior', 'w', 'rgba(199, 156, 110, 0.6)', '#C79C6E'),
(2, 'Paladin', 'p', 'rgba(245, 140, 186, 0.6)', '#F58CBA'),
(3, 'Hunter', 'h', 'rgba(102, 160, 77, 0.6)', '#ABD473'),
(4, 'Rogue', 'r', 'rgba(255, 245, 105, 0.6)', '#FFF569'),
(5, 'Priest', 'pr', 'rgba(255, 255, 255, 0.6)', '#FFFFFF'),
(6, 'Death Knight', 'dk', 'rgba(196, 31, 59, 0.6)', '#C41F3B'),
(7, 'Shaman', 's', 'rgba(0, 112, 222, 0.6)', '#0070DE'),
(8, 'Mage', 'm', 'rgba(105, 204, 240, 0.6)', '#69CCF0'),
(9, 'Warlock', 'wl', 'rgba(148, 130, 201, 0.6)', '#9482C9'),
(10, 'Monk', 'mk', 'rgba(0, 255, 150, 0.6)', '#00FF96'),
(11, 'Druid', 'd', 'rgba(255, 125, 10, 0.6)', '#FF7D0A'),
(12, 'Demon Hunter', 'dh', 'rgba(163, 48, 201, 0.6)', '#A330C9');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
