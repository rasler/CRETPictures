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
-- Structure de la table `cret_permissions`
--

DROP TABLE IF EXISTS `cret_permissions`;
CREATE TABLE IF NOT EXISTS `cret_permissions` (
  `uid` int(10) unsigned NOT NULL,
  `perm` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  KEY `uid` (`uid`,`perm`)
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
-- Contraintes pour la table `cret_permissions`
--
ALTER TABLE `cret_permissions`
  ADD CONSTRAINT `cret_permissions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `cret_users` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;
