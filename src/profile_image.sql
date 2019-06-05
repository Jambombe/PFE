-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mer 05 Juin 2019 à 11:19
-- Version du serveur :  5.7.26-0ubuntu0.18.04.1
-- Version de PHP :  7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

-- CREATE TABLE `profile_image` (
--   `id` int(11) NOT NULL,
--   `url` varchar(1023) COLLATE utf8mb4_unicode_ci NOT NULL,
--   `is_local` tinyint(1) NOT NULL,
--   `price` int(11) NOT NULL,
--   `required_level` int(11) NOT NULL,
--   `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `profile_image`
--

INSERT INTO `profile_image` (`id`, `url`, `is_local`, `price`, `required_level`, `name`) VALUES
(1, '/assets/quest-book/img/profile_images/adventure_map.png', 1, 2, 1, 'Carte au tresor'),
(2, '/assets/quest-book/img/profile_images/bag.png', 1, 0, 1, 'Sac'),
(3, '/assets/quest-book/img/profile_images/castle.png', 1, 0, 1, 'Château'),
(4, '/assets/quest-book/img/profile_images/centaur.png', 1, 0, 1, 'Centaure'),
(5, '/assets/quest-book/img/profile_images/dragon.png', 1, 0, 1, 'Dragon'),
(6, '/assets/quest-book/img/profile_images/dragon_egg.png', 1, 0, 1, 'Œuf de dragon'),
(7, '/assets/quest-book/img/profile_images/elf.png', 1, 0, 1, 'Elfe'),
(8, '/assets/quest-book/img/profile_images/fireball.png', 1, 0, 1, 'Boule de feu'),
(9, '/assets/quest-book/img/profile_images/grim_reaper.png', 1, 0, 1, 'Faucheur'),
(10, '/assets/quest-book/img/profile_images/king.png', 1, 0, 1, 'Roi'),
(11, '/assets/quest-book/img/profile_images/knight.png', 1, 0, 1, 'Chevalier'),
(12, '/assets/quest-book/img/profile_images/magic_ball.png', 1, 0, 1, 'Boule de cristal'),
(13, '/assets/quest-book/img/profile_images/orc.png', 1, 0, 1, 'Orc'),
(14, '/assets/quest-book/img/profile_images/potion.png', 1, 0, 1, 'Potion'),
(15, '/assets/quest-book/img/profile_images/scroll.png', 1, 0, 1, 'Parchemin'),
(16, '/assets/quest-book/img/profile_images/spell_book.png', 1, 0, 1, 'Livre'),
(17, '/assets/quest-book/img/profile_images/spider.png', 1, 0, 1, 'Araignée'),
(18, '/assets/quest-book/img/profile_images/sword.png', 1, 0, 1, 'Épée'),
(19, '/assets/quest-book/img/profile_images/sword2.png', 1, 0, 1, 'Épée large'),
(20, '/assets/quest-book/img/profile_images/treant.png', 1, 0, 1, 'Tréant'),
(21, '/assets/quest-book/img/profile_images/unicorn.png', 1, 0, 1, 'Licorne'),
(22, '/assets/quest-book/img/profile_images/viking.png', 1, 0, 1, 'Viking'),
(23, '/assets/quest-book/img/profile_images/villager.png', 1, 0, 1, 'Villageois'),
(24, '/assets/quest-book/img/profile_images/werewolf.png', 1, 0, 1, 'Loup garou');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `profile_image`
--
-- ALTER TABLE `profile_image`
--   ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `profile_image`
--
-- ALTER TABLE `profile_image`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
