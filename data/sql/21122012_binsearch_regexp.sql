-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 21 Décembre 2012 à 13:03
-- Version du serveur: 5.5.28
-- Version de PHP: 5.4.6-2~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `allinone`
--

-- --------------------------------------------------------

--
-- Structure de la table `binsearch_regex`
--

CREATE TABLE IF NOT EXISTS `binsearch_regex` (
  `binsearch_regex_id` int(11) NOT NULL AUTO_INCREMENT,
  `binsearch_group_id` int(11) DEFAULT NULL,
  `pattern` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `rank` int(1) NOT NULL DEFAULT '0',
  `min_size` bigint(20) NOT NULL,
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`binsearch_regex_id`),
  KEY `binsearch_group_id` (`binsearch_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Contenu de la table `binsearch_regex`
--

INSERT INTO `binsearch_regex` (`binsearch_regex_id`, `binsearch_group_id`, `pattern`, `rank`, `min_size`, `update`) VALUES
(3, 6, '[^"]*"Mike (re)?post (?<name>[^\\.]*)\\.(?<season>\\d?\\d)(?<episode>\\d{2})[^"]*".*', 0, 80000, '2012-03-30 14:49:32'),
(4, 6, '[^"]*"(?<name>.*)[\\. ](?<season>\\d+)x(?<episode>\\d+)[^"]*".*', 1, 80000, '2012-03-30 14:49:32'),
(5, 6, '[^"]*"(?<name>.*)\\.S(?<season>\\d+)E(?<episode>\\d+)[^"]*".*', 2, 80000, '2012-03-30 14:49:32'),
(6, 6, '[^"]*"(?<name>.*)\\.(?<season>\\d)(?<episode>\\d{2})[\\._][^"]*".*', 3, 80000, '2012-03-30 14:49:32'),
(8, 7, '[^"]*"(\\d+)?(?<name>.*)S(?<season>\\d+)E(?<episode>\\d+)[^"]*".*', 0, 80000, '2012-03-30 14:49:32'),
(13, 11, '.+\\[\\#a.b.teevee@EFNet\\]-\\[(?<name>[^\\[]*)\\.S(?<season>\\d{2})E(?<episode>\\d{2}).+', 0, 100000, '2012-11-08 19:37:24'),
(14, 6, '[^"]*"DTC=(?<name>[^\\.]*)(?<season>\\d)(?<episode>\\d{2}).*', 0, 80000, '2012-12-21 11:41:08');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;