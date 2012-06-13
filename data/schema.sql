-- phpMyAdmin SQL Dump
-- version 3.4.11deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 28 Mai 2012 à 15:04
-- Version du serveur: 5.1.61
-- Version de PHP: 5.4.0-3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `ir`
--

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article`
--

DROP TABLE IF EXISTS `magazine_article`;
CREATE TABLE IF NOT EXISTS `magazine_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_type_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `abstract` text,
  `citation` varchar(255) DEFAULT NULL,
  `body` text,
  `view` int(11) unsigned NOT NULL DEFAULT '0',
  `video_link` varchar(255) DEFAULT NULL,
  `is_online` int(1) unsigned NOT NULL DEFAULT '0',
  `is_promoted` int(1) unsigned NOT NULL DEFAULT '0',
  `is_headline` int(1) unsigned NOT NULL DEFAULT '0',
  `is_validate` int(1) unsigned NOT NULL,
  `is_article` int(1) unsigned NOT NULL DEFAULT '1',
  `has_comment` int(1) unsigned NOT NULL DEFAULT '1',
  `has_twitter` int(1) unsigned NOT NULL DEFAULT '1',
  `has_facebook` int(1) unsigned NOT NULL DEFAULT '1',
  `has_push_social` int(1) unsigned NOT NULL DEFAULT '1',
  `has_related` int(1) unsigned NOT NULL DEFAULT '1',
  `author_id` int(11) unsigned NOT NULL,
  `cover_id` varchar(100) DEFAULT NULL,
  `picture_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `published_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `is_contributed` int(1) DEFAULT NULL,
  `is_moderated` int(1) DEFAULT NULL,
  `associated_link` varchar(255) DEFAULT NULL,
  `is_homepage` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`author_id`),
  KEY `fk_magazine_article_magazine_author1` (`author_id`),
  KEY `is_contributed` (`is_contributed`),
  KEY `is_moderated` (`is_moderated`),
  KEY `content_type_id` (`content_type_id`),
  KEY `slug` (`slug`),
  KEY `view` (`view`),
  KEY `is_online` (`is_online`),
  KEY `is_headline` (`is_headline`),
  KEY `is_article` (`is_article`),
  KEY `cover_id` (`cover_id`),
  KEY `picture_id` (`picture_id`),
  KEY `published_at` (`published_at`),
  KEY `is_promoted` (`is_promoted`),
  KEY `is_validate` (`is_validate`),
  KEY `has_comment` (`has_comment`),
  KEY `has_twitter` (`has_twitter`),
  KEY `has_facebook` (`has_facebook`),
  KEY `has_push_social` (`has_push_social`),
  KEY `has_related` (`has_related`),
  KEY `is_homepage` (`is_homepage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=339 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article_article`
--

DROP TABLE IF EXISTS `magazine_article_article`;
CREATE TABLE IF NOT EXISTS `magazine_article_article` (
  `parent_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  `magazine_id` int(11) unsigned DEFAULT '1',
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`parent_id`,`child_id`),
  KEY `fk_magzine_article_article_magazine_article` (`parent_id`),
  KEY `fk_magzine_article_article_magazine_article1` (`child_id`),
  KEY `fk_magzine_article_article_magazine_magazine1` (`magazine_id`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article_category`
--

DROP TABLE IF EXISTS `magazine_article_category`;
CREATE TABLE IF NOT EXISTS `magazine_article_category` (
  `article_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `magazine_id` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`article_id`,`category_id`,`magazine_id`),
  KEY `fk_magazine_article_category_magazine_article1` (`article_id`),
  KEY `fk_magazine_article_category_magazine_category1` (`category_id`),
  KEY `magazine_id` (`magazine_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article_content`
--

DROP TABLE IF EXISTS `magazine_article_content`;
CREATE TABLE IF NOT EXISTS `magazine_article_content` (
  `article_id` int(11) unsigned NOT NULL,
  `proxy_pk` int(11) unsigned NOT NULL,
  `proxy_model` int(11) unsigned NOT NULL,
  `magazine_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`article_id`,`proxy_pk`,`proxy_model`),
  KEY `fk_magazine_article_content_magazine_article1` (`article_id`),
  KEY `fk_magazine_article_content_magazine_magazine1` (`magazine_id`),
  KEY `proxy_model` (`proxy_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article_media`
--

DROP TABLE IF EXISTS `magazine_article_media`;
CREATE TABLE IF NOT EXISTS `magazine_article_media` (
  `media_id` int(11) unsigned NOT NULL,
  `article_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`media_id`,`article_id`),
  KEY `fk_magazine_article_media_magazine_media1` (`media_id`),
  KEY `fk_magazine_article_media_magazine_article1` (`article_id`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_article_tag`
--

DROP TABLE IF EXISTS `magazine_article_tag`;
CREATE TABLE IF NOT EXISTS `magazine_article_tag` (
  `tag_id` int(11) unsigned NOT NULL,
  `proxy_pk` int(11) unsigned NOT NULL,
  `proxy_model` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`,`proxy_model`,`proxy_pk`),
  KEY `fk_magazine_article_tag_magazine_tag1` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_author`
--

DROP TABLE IF EXISTS `magazine_author`;
CREATE TABLE IF NOT EXISTS `magazine_author` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(45) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `abstract` text,
  `body` text,
  `url_twitter` varchar(255) DEFAULT NULL,
  `url_facebook` varchar(255) DEFAULT NULL,
  `url_googleplus` varchar(255) DEFAULT NULL,
  `url_blog` varchar(255) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `picture_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `user_id` (`user_id`),
  KEY `picture_id` (`picture_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_category`
--

DROP TABLE IF EXISTS `magazine_category`;
CREATE TABLE IF NOT EXISTS `magazine_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `body` text,
  `css_class` varchar(45) DEFAULT NULL,
  `pagination_item` int(11) unsigned DEFAULT '20' COMMENT 'Number of items per page',
  `is_online` int(1) unsigned NOT NULL DEFAULT '0',
  `cover_id` varchar(100) DEFAULT NULL,
  `icon_id` varchar(100) DEFAULT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `is_online` (`is_online`),
  KEY `cover_id` (`cover_id`),
  KEY `icon_id` (`icon_id`),
  KEY `parent_id` (`parent_id`),
  KEY `language_id` (`language_id`),
  KEY `original_id` (`original_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_highlight`
--

DROP TABLE IF EXISTS `magazine_highlight`;
CREATE TABLE IF NOT EXISTS `magazine_highlight` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) unsigned DEFAULT NULL,
  `magazine_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_magazine_highlight_magazine_magazine1` (`magazine_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_magazine`
--

DROP TABLE IF EXISTS `magazine_magazine`;
CREATE TABLE IF NOT EXISTS `magazine_magazine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `logo_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `logo_id` (`logo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_magazine_article`
--

DROP TABLE IF EXISTS `magazine_magazine_article`;
CREATE TABLE IF NOT EXISTS `magazine_magazine_article` (
  `article_id` int(11) unsigned NOT NULL,
  `magazine_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`magazine_id`),
  KEY `fk_magazine_magazine_article_magazine_magazine1` (`magazine_id`),
  KEY `fk_magazine_magazine_article_magazine_article1` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_media`
--

DROP TABLE IF EXISTS `magazine_media`;
CREATE TABLE IF NOT EXISTS `magazine_media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `author_id` int(11) unsigned DEFAULT NULL,
  `has_comment` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content_type_id` int(11) unsigned NOT NULL DEFAULT '13',
  `is_valid` int(1) DEFAULT NULL,
  `is_contributed` int(1) DEFAULT NULL,
  `is_moderated` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_magazine_media_magazine_author1` (`author_id`),
  KEY `is_valid` (`is_valid`),
  KEY `is_contributed` (`is_contributed`),
  KEY `is_moderated` (`is_moderated`),
  KEY `slug` (`slug`),
  KEY `media_id` (`media_id`),
  KEY `language_id` (`language_id`),
  KEY `original_id` (`original_id`),
  KEY `content_type_id` (`content_type_id`),
  KEY `has_comment` (`has_comment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_post`
--

DROP TABLE IF EXISTS `magazine_post`;
CREATE TABLE IF NOT EXISTS `magazine_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT '',
  `abstract` text,
  `body` text NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_online` int(1) unsigned DEFAULT NULL,
  `is_promoted` int(1) unsigned DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_tag`
--

DROP TABLE IF EXISTS `magazine_tag`;
CREATE TABLE IF NOT EXISTS `magazine_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Structure de la table `magazine_tag_proxy`
--

DROP TABLE IF EXISTS `magazine_tag_proxy`;
CREATE TABLE IF NOT EXISTS `magazine_tag_proxy` (
  `tag_id` int(11) unsigned NOT NULL,
  `proxy_pk` int(11) unsigned NOT NULL,
  `proxy_model` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`,`proxy_model`,`proxy_pk`),
  KEY `fk_magazine_article_tag_magazine_tag1` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `magazine_article`
--
ALTER TABLE `magazine_article`
  ADD CONSTRAINT `fk_magazine_article_magazine_author1` FOREIGN KEY (`author_id`) REFERENCES `magazine_author` (`id`),
  ADD CONSTRAINT `magazine_article_ibfk_1` FOREIGN KEY (`content_type_id`) REFERENCES `centurion_content_type` (`id`),
  ADD CONSTRAINT `magazine_article_ibfk_2` FOREIGN KEY (`cover_id`) REFERENCES `media_file` (`id`),
  ADD CONSTRAINT `magazine_article_ibfk_3` FOREIGN KEY (`picture_id`) REFERENCES `media_file` (`id`);

--
-- Contraintes pour la table `magazine_article_article`
--
ALTER TABLE `magazine_article_article`
  ADD CONSTRAINT `fk_magzine_article_article_magazine_article` FOREIGN KEY (`parent_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magzine_article_article_magazine_article1` FOREIGN KEY (`child_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magzine_article_article_magazine_magazine1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine_magazine` (`id`);

--
-- Contraintes pour la table `magazine_article_category`
--
ALTER TABLE `magazine_article_category`
  ADD CONSTRAINT `fk_magazine_article_category_magazine_article1` FOREIGN KEY (`article_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magazine_article_category_magazine_category1` FOREIGN KEY (`category_id`) REFERENCES `magazine_category` (`id`),
  ADD CONSTRAINT `magazine_article_category_ibfk_1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine_magazine` (`id`);

--
-- Contraintes pour la table `magazine_article_content`
--
ALTER TABLE `magazine_article_content`
  ADD CONSTRAINT `fk_magazine_article_content_magazine_article1` FOREIGN KEY (`article_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magazine_article_content_magazine_magazine1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine_magazine` (`id`),
  ADD CONSTRAINT `magazine_article_content_ibfk_1` FOREIGN KEY (`proxy_model`) REFERENCES `centurion_content_type` (`id`);

--
-- Contraintes pour la table `magazine_article_media`
--
ALTER TABLE `magazine_article_media`
  ADD CONSTRAINT `fk_magazine_article_media_magazine_article1` FOREIGN KEY (`article_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magazine_article_media_magazine_media1` FOREIGN KEY (`media_id`) REFERENCES `magazine_media` (`id`);

--
-- Contraintes pour la table `magazine_article_tag`
--
ALTER TABLE `magazine_article_tag`
  ADD CONSTRAINT `fk_magazine_article_tag_magazine_tag1` FOREIGN KEY (`tag_id`) REFERENCES `magazine_tag` (`id`);

--
-- Contraintes pour la table `magazine_author`
--
ALTER TABLE `magazine_author`
  ADD CONSTRAINT `magazine_author_ibfk_1` FOREIGN KEY (`picture_id`) REFERENCES `media_file` (`id`),
  ADD CONSTRAINT `magazine_author_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

--
-- Contraintes pour la table `magazine_category`
--
ALTER TABLE `magazine_category`
  ADD CONSTRAINT `magazine_category_ibfk_1` FOREIGN KEY (`cover_id`) REFERENCES `media_file` (`id`),
  ADD CONSTRAINT `magazine_category_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `media_file` (`id`),
  ADD CONSTRAINT `magazine_category_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `magazine_category` (`id`);

--
-- Contraintes pour la table `magazine_highlight`
--
ALTER TABLE `magazine_highlight`
  ADD CONSTRAINT `fk_magazine_highlight_magazine_magazine1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine_magazine` (`id`),
  ADD CONSTRAINT `fk_magazine_home_magazine_article1` FOREIGN KEY (`id`) REFERENCES `magazine_article` (`id`);

--
-- Contraintes pour la table `magazine_magazine`
--
ALTER TABLE `magazine_magazine`
  ADD CONSTRAINT `magazine_magazine_ibfk_1` FOREIGN KEY (`logo_id`) REFERENCES `media_file` (`id`);

--
-- Contraintes pour la table `magazine_magazine_article`
--
ALTER TABLE `magazine_magazine_article`
  ADD CONSTRAINT `fk_magazine_magazine_article_magazine_article1` FOREIGN KEY (`article_id`) REFERENCES `magazine_article` (`id`),
  ADD CONSTRAINT `fk_magazine_magazine_article_magazine_magazine1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine_magazine` (`id`);

--
-- Contraintes pour la table `magazine_media`
--
ALTER TABLE `magazine_media`
  ADD CONSTRAINT `fk_magazine_media_magazine_author1` FOREIGN KEY (`author_id`) REFERENCES `magazine_author` (`id`),
  ADD CONSTRAINT `magazine_media_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media_file` (`id`),
  ADD CONSTRAINT `magazine_media_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`),
  ADD CONSTRAINT `magazine_media_ibfk_3` FOREIGN KEY (`original_id`) REFERENCES `magazine_media` (`id`),
  ADD CONSTRAINT `magazine_media_ibfk_4` FOREIGN KEY (`content_type_id`) REFERENCES `centurion_content_type` (`id`);

--
-- Contraintes pour la table `magazine_tag`
--
ALTER TABLE `magazine_tag`
  ADD CONSTRAINT `magazine_tag_ibfk_1` FOREIGN KEY (`original_id`) REFERENCES `magazine_tag` (`id`),
  ADD CONSTRAINT `magazine_tag_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`);
SET FOREIGN_KEY_CHECKS=1;
