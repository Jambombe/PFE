-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 28 mai 2019 à 10:45
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `profile_image`
--

DROP TABLE IF EXISTS `profile_image`;
CREATE TABLE IF NOT EXISTS `profile_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(1023) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_local` tinyint(1) NOT NULL,
  `price` int(11) NOT NULL,
  `required_level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profile_image`
--

INSERT INTO `profile_image` (`id`, `url`, `is_local`, `price`, `required_level`) VALUES
(1, '/assets/quest-book/img/profile_images/adventure_map.png', 1, 2, 1),
(2, '/assets/quest-book/img/profile_images/bag.png', 1, 0, 1),
(3, '/assets/quest-book/img/profile_images/castle.png', 1, 0, 1),
(4, '/assets/quest-book/img/profile_images/centaur.png', 1, 0, 1),
(5, '/assets/quest-book/img/profile_images/dragon.png', 1, 0, 1),
(6, '/assets/quest-book/img/profile_images/dragon_egg.png', 1, 0, 1),
(7, '/assets/quest-book/img/profile_images/elf.png', 1, 0, 1),
(8, '/assets/quest-book/img/profile_images/fireball.png', 1, 0, 1),
(9, '/assets/quest-book/img/profile_images/grim_reaper.png', 1, 0, 1),
(10, '/assets/quest-book/img/profile_images/king.png', 1, 0, 1),
(11, '/assets/quest-book/img/profile_images/knight.png', 1, 0, 1),
(12, '/assets/quest-book/img/profile_images/magic_ball.png', 1, 0, 1),
(13, '/assets/quest-book/img/profile_images/orc.png', 1, 0, 1),
(14, '/assets/quest-book/img/profile_images/potion.png', 1, 0, 1),
(15, '/assets/quest-book/img/profile_images/scroll.png', 1, 0, 1),
(16, '/assets/quest-book/img/profile_images/spell_book.png', 1, 0, 1),
(17, '/assets/quest-book/img/profile_images/spider.png', 1, 0, 1),
(18, '/assets/quest-book/img/profile_images/sword.png', 1, 0, 1),
(19, '/assets/quest-book/img/profile_images/sword2.png', 1, 0, 1),
(20, '/assets/quest-book/img/profile_images/treant.png', 1, 0, 1),
(21, '/assets/quest-book/img/profile_images/unicorn.png', 1, 0, 1),
(22, '/assets/quest-book/img/profile_images/viking.png', 1, 0, 1),
(23, '/assets/quest-book/img/profile_images/villager.png', 1, 0, 1),
(24, '/assets/quest-book/img/profile_images/werewolf.png', 1, 0, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
