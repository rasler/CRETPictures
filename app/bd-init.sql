-- phpMyAdmin SQL Dump
-- version 3.4.5

--  Note : les tables seront générées avec le préfixe "cret_"

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `cret`
--

-- --------------------------------------------------------

--
-- Structure de la table `cret_invitations`
--

CREATE TABLE IF NOT EXISTS `cret_invitations` (
  `source` int(11) unsigned NOT NULL,
  `destination` int(11) unsigned NOT NULL,
  `profile` int(10) unsigned NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`source`,`destination`),
  UNIQUE KEY `source` (`source`,`profile`),
  KEY `profile` (`profile`),
  KEY `destination` (`destination`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cret_permissions`
--

DROP TABLE IF EXISTS `cret_permissions`;
CREATE TABLE IF NOT EXISTS `cret_permissions` (
  `uid` int(10) unsigned NOT NULL,
  `perm` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`uid`,`perm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cret_profiles`
--

CREATE TABLE IF NOT EXISTS `cret_profiles` (
  `prid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner` int(10) unsigned NOT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`prid`),
  UNIQUE KEY `owner` (`owner`,`email`),
  UNIQUE KEY `owner_2` (`owner`,`link`),
  KEY `link` (`link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `cret_pictures`
--

CREATE TABLE IF NOT EXISTS `cret_pictures` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `publication` datetime NOT NULL,
  `creation` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `uid` (`uid`,`file`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cret_shares`
--

CREATE TABLE IF NOT EXISTS `cret_shares` (
  `pid` int(10) unsigned NOT NULL,
  `prid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pid`,`prid`),
  KEY `prid` (`prid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cret_users`
--

DROP TABLE IF EXISTS `cret_users`;
CREATE TABLE IF NOT EXISTS `cret_users` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `pass` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `creation` datetime NOT NULL,
  `lastConnection` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cret_invitations`
--
ALTER TABLE `cret_invitations`
  ADD CONSTRAINT `cret_invitations_ibfk_5` FOREIGN KEY (`profile`) REFERENCES `cret_profiles` (`prid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cret_invitations_ibfk_3` FOREIGN KEY (`source`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cret_invitations_ibfk_4` FOREIGN KEY (`destination`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cret_permissions`
--
ALTER TABLE `cret_permissions`
  ADD CONSTRAINT `cret_permissions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `cret_profiles`
--
ALTER TABLE `cret_profiles`
  ADD CONSTRAINT `cret_profiles_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cret_profiles_ibfk_2` FOREIGN KEY (`link`) REFERENCES `cret_users` (`uid`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Contraintes pour la table `cret_pictures`
--
ALTER TABLE `cret_pictures`
  ADD CONSTRAINT `cret_pictures_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `cret_shares`
--
ALTER TABLE `cret_shares`
  ADD CONSTRAINT `cret_shares_ibfk_2` FOREIGN KEY (`prid`) REFERENCES `cret_profiles` (`prid`) ON DELETE CASCADE,
  ADD CONSTRAINT `cret_shares_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `cret_pictures` (`pid`) ON DELETE CASCADE;