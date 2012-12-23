
CREATE TABLE IF NOT EXISTS `binnews_category` (
  `binnews_category_id` int(11) NOT NULL,
  `binnews_category_label` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `binnews_category_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `refreshed` datetime DEFAULT NULL,
  PRIMARY KEY (`binnews_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `binnews_category`
--

INSERT INTO `binnews_category` (`binnews_category_id`, `binnews_category_label`, `binnews_category_code`, `active`, `refreshed`) VALUES
(7, 'Séries', '', 1, '2012-12-23 22:34:57'),
(24, 'Anime', 'anime', 1, '2012-12-23 22:34:58'),
(26, 'Séries DVD', 'seriedvd', 1, '2012-12-23 22:34:58'),
(44, 'Séries HD', 'seriehd', 1, '2012-12-23 22:34:59'),
(52, 'Anime DVD', 'animedvd', 1, '2012-12-23 22:34:59'),
(53, 'Anime HD', 'animhd', 1, '2012-12-23 22:35:00'),
(56, 'Séries VO', 'serievo', 1, '2012-12-23 22:35:01');

CREATE TABLE IF NOT EXISTS `binnews` (
  `binnews_id` int(11) NOT NULL,
  `binnews_category_id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `pubDate` datetime NOT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`binnews_id`),
  KEY `category_id` (`binnews_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `binnews`
--
ALTER TABLE `binnews`
  ADD CONSTRAINT `binnews_ibfk_1` FOREIGN KEY (`binnews_category_id`) REFERENCES `binnews_category` (`binnews_category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE  `binsearch_group` CHANGE  `update`  `refreshed` DATETIME NULL DEFAULT NULL;