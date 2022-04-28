-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 20 avr. 2022 à 07:31
-- Version du serveur :  5.7.30
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `usbeaufort`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220406123835', '2022-04-06 12:38:43', 168),
('DoctrineMigrations\\Version20220406132244', '2022-04-06 13:23:17', 319),
('DoctrineMigrations\\Version20220406132443', '2022-04-06 13:24:48', 200),
('DoctrineMigrations\\Version20220406132937', '2022-04-06 13:29:40', 235),
('DoctrineMigrations\\Version20220406133352', '2022-04-06 13:33:56', 123),
('DoctrineMigrations\\Version20220406133946', '2022-04-06 13:39:51', 311),
('DoctrineMigrations\\Version20220406140809', '2022-04-06 14:08:14', 436),
('DoctrineMigrations\\Version20220406141053', '2022-04-06 14:11:00', 301),
('DoctrineMigrations\\Version20220408074424', '2022-04-08 07:46:00', 119),
('DoctrineMigrations\\Version20220408080204', '2022-04-08 08:02:09', 481),
('DoctrineMigrations\\Version20220408080731', '2022-04-08 08:07:34', 229),
('DoctrineMigrations\\Version20220408123227', '2022-04-08 12:32:31', 249),
('DoctrineMigrations\\Version20220408132838', '2022-04-08 13:28:42', 368),
('DoctrineMigrations\\Version20220412064449', '2022-04-12 07:14:04', 214),
('DoctrineMigrations\\Version20220412072354', '2022-04-12 07:23:59', 197),
('DoctrineMigrations\\Version20220412080536', '2022-04-12 08:05:39', 371),
('DoctrineMigrations\\Version20220412120330', '2022-04-12 12:03:35', 250),
('DoctrineMigrations\\Version20220412122440', '2022-04-12 12:24:44', 226),
('DoctrineMigrations\\Version20220412123114', '2022-04-12 12:31:17', 431),
('DoctrineMigrations\\Version20220412124115', '2022-04-12 12:41:19', 215),
('DoctrineMigrations\\Version20220413040759', '2022-04-13 04:08:06', 666),
('DoctrineMigrations\\Version20220413043540', '2022-04-13 04:41:43', 324),
('DoctrineMigrations\\Version20220413044532', '2022-04-13 04:45:49', 339),
('DoctrineMigrations\\Version20220413044619', '2022-04-13 04:46:23', 304),
('DoctrineMigrations\\Version20220413044640', '2022-04-13 04:46:44', 309),
('DoctrineMigrations\\Version20220413045853', '2022-04-13 04:59:00', 333),
('DoctrineMigrations\\Version20220418051930', '2022-04-18 05:19:35', 352),
('DoctrineMigrations\\Version20220418054408', '2022-04-18 05:44:12', 376),
('DoctrineMigrations\\Version20220418061932', '2022-04-18 06:19:36', 494),
('DoctrineMigrations\\Version20220418062121', '2022-04-18 06:29:41', 306),
('DoctrineMigrations\\Version20220418063032', '2022-04-18 06:30:35', 307),
('DoctrineMigrations\\Version20220418111559', '2022-04-18 11:16:03', 471),
('DoctrineMigrations\\Version20220418125049', '2022-04-18 12:50:55', 309),
('DoctrineMigrations\\Version20220418125209', '2022-04-18 12:52:12', 287),
('DoctrineMigrations\\Version20220418125338', '2022-04-18 12:53:41', 362),
('DoctrineMigrations\\Version20220418125406', '2022-04-18 12:54:08', 156),
('DoctrineMigrations\\Version20220419025708', '2022-04-19 02:57:14', 393),
('DoctrineMigrations\\Version20220419030517', '2022-04-19 03:05:21', 334),
('DoctrineMigrations\\Version20220419031710', '2022-04-19 03:17:14', 329),
('DoctrineMigrations\\Version20220419041728', '2022-04-19 04:17:31', 257),
('DoctrineMigrations\\Version20220419050657', '2022-04-19 05:07:04', 273),
('DoctrineMigrations\\Version20220419050905', '2022-04-19 05:09:09', 246),
('DoctrineMigrations\\Version20220419064254', '2022-04-19 06:42:57', 266),
('DoctrineMigrations\\Version20220419071509', '2022-04-19 07:15:14', 267);

-- --------------------------------------------------------

--
-- Structure de la table `usb_fields`
--

CREATE TABLE `usb_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_fields`
--

INSERT INTO `usb_fields` (`id`, `name`) VALUES
(1, 'Terrain 1'),
(2, 'Terrain 2');

-- --------------------------------------------------------

--
-- Structure de la table `usb_groups`
--

CREATE TABLE `usb_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_en_cours_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_groups`
--

INSERT INTO `usb_groups` (`id`, `name`, `phase_en_cours_id`) VALUES
(1, 'U11', 1),
(2, 'U13', NULL),
(3, 'U13F', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usb_meets`
--

CREATE TABLE `usb_meets` (
  `id` int(11) NOT NULL,
  `team_a_id` int(11) DEFAULT NULL,
  `team_b_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `phase_id` int(11) NOT NULL,
  `team_forfait_id` int(11) DEFAULT NULL,
  `score_a` int(11) DEFAULT NULL,
  `score_b` int(11) DEFAULT NULL,
  `penalty_a` int(11) DEFAULT NULL,
  `penalty_b` int(11) DEFAULT NULL,
  `tour` int(11) DEFAULT NULL,
  `principal` tinyint(1) DEFAULT NULL,
  `poule_id` int(11) DEFAULT NULL,
  `position_a_id` int(11) DEFAULT NULL,
  `position_b_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_meets`
--

INSERT INTO `usb_meets` (`id`, `team_a_id`, `team_b_id`, `field_id`, `phase_id`, `team_forfait_id`, `score_a`, `score_b`, `penalty_a`, `penalty_b`, `tour`, `principal`, `poule_id`, `position_a_id`, `position_b_id`, `name`) VALUES
(20617, 37, 38, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5474, NULL, NULL, NULL),
(20618, 37, 39, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5474, NULL, NULL, NULL),
(20619, 37, 40, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5474, NULL, NULL, NULL),
(20620, 38, 39, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5474, NULL, NULL, NULL),
(20621, 38, 40, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5474, NULL, NULL, NULL),
(20622, 39, 40, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5474, NULL, NULL, NULL),
(20623, 41, 42, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5475, NULL, NULL, NULL),
(20624, 41, 43, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5475, NULL, NULL, NULL),
(20625, 41, 44, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5475, NULL, NULL, NULL),
(20626, 42, 43, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5475, NULL, NULL, NULL),
(20627, 42, 44, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5475, NULL, NULL, NULL),
(20628, 43, 44, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5475, NULL, NULL, NULL),
(20629, 45, 46, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5476, NULL, NULL, NULL),
(20630, 45, 47, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5476, NULL, NULL, NULL),
(20631, 45, 48, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5476, NULL, NULL, NULL),
(20632, 46, 47, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5476, NULL, NULL, NULL),
(20633, 46, 48, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5476, NULL, NULL, NULL),
(20634, 47, 48, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5476, NULL, NULL, NULL),
(20635, 49, 50, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5477, NULL, NULL, NULL),
(20636, 49, 51, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5477, NULL, NULL, NULL),
(20637, 49, 52, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5477, NULL, NULL, NULL),
(20638, 50, 51, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5477, NULL, NULL, NULL),
(20639, 50, 52, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5477, NULL, NULL, NULL),
(20640, 51, 52, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5477, NULL, NULL, NULL),
(20641, 53, 54, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5478, NULL, NULL, NULL),
(20642, 53, 55, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5478, NULL, NULL, NULL),
(20643, 53, 56, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5478, NULL, NULL, NULL),
(20644, 54, 55, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5478, NULL, NULL, NULL),
(20645, 54, 56, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5478, NULL, NULL, NULL),
(20646, 55, 56, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5478, NULL, NULL, NULL),
(20647, 57, 58, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5479, NULL, NULL, NULL),
(20648, 57, 59, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5479, NULL, NULL, NULL),
(20649, 57, 60, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5479, NULL, NULL, NULL),
(20650, 58, 59, NULL, 5, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5479, NULL, NULL, NULL),
(20651, 58, 60, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5479, NULL, NULL, NULL),
(20652, 59, 60, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5479, NULL, NULL, NULL),
(20653, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5481, 13119, 13131, NULL),
(20654, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5481, 13119, 13134, NULL),
(20655, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5481, 13119, 13137, NULL),
(20656, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5481, 13131, 13134, NULL),
(20657, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5481, 13131, 13137, NULL),
(20658, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5481, 13134, 13137, NULL),
(20659, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5483, 13118, 13121, NULL),
(20660, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5483, 13118, 13127, NULL),
(20661, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5483, 13118, 13138, NULL),
(20662, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5483, 13121, 13127, NULL),
(20663, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5483, 13121, 13138, NULL),
(20664, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5483, 13127, 13138, NULL),
(20665, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5480, 13122, 13126, NULL),
(20666, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5480, 13122, 13129, NULL),
(20667, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5480, 13122, 13133, NULL),
(20668, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5480, 13126, 13129, NULL),
(20669, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5480, 13126, 13133, NULL),
(20670, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5480, 13129, 13133, NULL),
(20671, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5482, 13117, 13123, NULL),
(20672, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5482, 13117, 13125, NULL),
(20673, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5482, 13117, 13130, NULL),
(20674, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 1, 5482, 13123, 13125, NULL),
(20675, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 1, 5482, 13123, 13130, NULL),
(20676, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 1, 5482, 13125, 13130, NULL),
(20677, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 0, 5484, 13128, 13132, NULL),
(20678, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 0, 5484, 13128, 13135, NULL),
(20679, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 0, 5484, 13128, 13139, NULL),
(20680, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 0, 5484, 13132, 13135, NULL),
(20681, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 0, 5484, 13132, 13139, NULL),
(20682, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 0, 5484, 13135, 13139, NULL),
(20683, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 0, 5485, 13120, 13124, NULL),
(20684, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 0, 5485, 13120, 13136, NULL),
(20685, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 0, 5485, 13120, 13140, NULL),
(20686, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, 0, 5485, 13124, 13136, NULL),
(20687, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, 0, 5485, 13124, 13140, NULL),
(20688, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, 0, 5485, 13136, 13140, NULL),
(20689, 9, 13, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5492, NULL, NULL, NULL),
(20690, 9, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5492, NULL, NULL, NULL),
(20691, 9, 10, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5492, NULL, NULL, NULL),
(20692, 13, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5492, NULL, NULL, NULL),
(20693, 13, 10, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5492, NULL, NULL, NULL),
(20694, 3, 10, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5492, NULL, NULL, NULL),
(20695, 8, 12, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5493, NULL, NULL, NULL),
(20696, 8, 4, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5493, NULL, NULL, NULL),
(20697, 8, 19, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5493, NULL, NULL, NULL),
(20698, 12, 4, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5493, NULL, NULL, NULL),
(20699, 12, 19, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5493, NULL, NULL, NULL),
(20700, 4, 19, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5493, NULL, NULL, NULL),
(20701, 5, 11, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5494, NULL, NULL, NULL),
(20702, 5, 35, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5494, NULL, NULL, NULL),
(20703, 5, 34, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5494, NULL, NULL, NULL),
(20704, 11, 35, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5494, NULL, NULL, NULL),
(20705, 11, 34, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5494, NULL, NULL, NULL),
(20706, 35, 34, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5494, NULL, NULL, NULL),
(20707, 1, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5495, NULL, NULL, NULL),
(20708, 1, 31, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5495, NULL, NULL, NULL),
(20709, 1, 30, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5495, NULL, NULL, NULL),
(20710, 2, 31, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5495, NULL, NULL, NULL),
(20711, 2, 30, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5495, NULL, NULL, NULL),
(20712, 31, 30, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5495, NULL, NULL, NULL),
(20713, 29, 28, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5496, NULL, NULL, NULL),
(20714, 29, 27, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5496, NULL, NULL, NULL),
(20715, 29, 26, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5496, NULL, NULL, NULL),
(20716, 28, 27, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5496, NULL, NULL, NULL),
(20717, 28, 26, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5496, NULL, NULL, NULL),
(20718, 27, 26, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5496, NULL, NULL, NULL),
(20719, 25, 24, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5497, NULL, NULL, NULL),
(20720, 25, 14, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5497, NULL, NULL, NULL),
(20721, 25, 15, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5497, NULL, NULL, NULL),
(20722, 24, 14, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5497, NULL, NULL, NULL),
(20723, 24, 15, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5497, NULL, NULL, NULL),
(20724, 14, 15, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5497, NULL, NULL, NULL),
(20725, 16, 17, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5498, NULL, NULL, NULL),
(20726, 16, 18, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5498, NULL, NULL, NULL),
(20727, 16, 36, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5498, NULL, NULL, NULL),
(20728, 17, 18, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5498, NULL, NULL, NULL),
(20729, 17, 36, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5498, NULL, NULL, NULL),
(20730, 18, 36, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5498, NULL, NULL, NULL),
(20731, 20, 21, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5499, NULL, NULL, NULL),
(20732, 20, 22, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5499, NULL, NULL, NULL),
(20733, 20, 23, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5499, NULL, NULL, NULL),
(20734, 21, 22, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, 5499, NULL, NULL, NULL),
(20735, 21, 23, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 5499, NULL, NULL, NULL),
(20736, 22, 23, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 5499, NULL, NULL, NULL),
(20737, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5500, 13165, 13169, NULL),
(20738, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5500, 13165, 13182, NULL),
(20739, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5500, 13165, 13185, NULL),
(20740, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5500, 13169, 13182, NULL),
(20741, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5500, 13169, 13185, NULL),
(20742, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5500, 13182, 13185, NULL),
(20743, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5502, 13178, 13186, NULL),
(20744, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5502, 13178, 13189, NULL),
(20745, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5502, 13178, 13193, NULL),
(20746, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5502, 13186, 13189, NULL),
(20747, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5502, 13186, 13193, NULL),
(20748, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5502, 13189, 13193, NULL),
(20749, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5505, 13171, 13187, NULL),
(20750, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5505, 13171, 13192, NULL),
(20751, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5505, 13171, 13195, NULL),
(20752, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5505, 13187, 13192, NULL),
(20753, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5505, 13187, 13195, NULL),
(20754, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5505, 13192, 13195, NULL),
(20755, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5501, 13166, 13174, NULL),
(20756, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5501, 13166, 13181, NULL),
(20757, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5501, 13166, 13194, NULL),
(20758, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5501, 13174, 13181, NULL),
(20759, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5501, 13174, 13194, NULL),
(20760, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5501, 13181, 13194, NULL),
(20761, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5504, 13175, 13179, NULL),
(20762, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5504, 13175, 13188, NULL),
(20763, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5504, 13175, 13196, NULL),
(20764, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5504, 13179, 13188, NULL),
(20765, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5504, 13179, 13196, NULL),
(20766, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5504, 13188, 13196, NULL),
(20767, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5503, 13170, 13173, NULL),
(20768, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5503, 13170, 13177, NULL),
(20769, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5503, 13170, 13190, NULL),
(20770, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 1, 5503, 13173, 13177, NULL),
(20771, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 1, 5503, 13173, 13190, NULL),
(20772, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, 5503, 13177, 13190, NULL),
(20773, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5506, 13167, 13176, NULL),
(20774, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5506, 13167, 13184, NULL),
(20775, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5506, 13167, 13191, NULL),
(20776, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5506, 13176, 13184, NULL),
(20777, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5506, 13176, 13191, NULL),
(20778, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5506, 13184, 13191, NULL),
(20779, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5507, 13168, 13172, NULL),
(20780, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5507, 13168, 13180, NULL),
(20781, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5507, 13168, 13183, NULL),
(20782, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 3, 0, 5507, 13172, 13180, NULL),
(20783, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 2, 0, 5507, 13172, 13183, NULL),
(20784, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 5507, 13180, 13183, NULL),
(20785, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5508, 13197, 13201, 'Demie Finale - 1'),
(20786, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5508, 13209, 13205, 'Demie Finale - 2'),
(20787, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5508, NULL, NULL, 'Finale des perdants'),
(20788, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5508, NULL, NULL, 'Finale des vainqueurs'),
(20789, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5509, 13206, 13198, 'Demie Finale - 1'),
(20790, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5509, 13210, 13202, 'Demie Finale - 2'),
(20791, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5509, NULL, NULL, 'Finale des perdants'),
(20792, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5509, NULL, NULL, 'Finale des vainqueurs'),
(20793, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5510, 13211, 13199, 'Demie Finale - 1'),
(20794, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5510, 13203, 13207, 'Demie Finale - 2'),
(20795, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5510, NULL, NULL, 'Finale des perdants'),
(20796, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5510, NULL, NULL, 'Finale des vainqueurs'),
(20797, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5511, 13212, 13200, 'Demie Finale - 1'),
(20798, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5511, 13204, 13208, 'Demie Finale - 2'),
(20799, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5511, NULL, NULL, 'Finale des perdants'),
(20800, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5511, NULL, NULL, 'Finale des vainqueurs'),
(20801, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5512, 13213, 13225, 'Demie Finale - 1'),
(20802, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5512, 13217, 13221, 'Demie Finale - 2'),
(20803, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5512, NULL, NULL, 'Finale des perdants'),
(20804, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5512, NULL, NULL, 'Finale des vainqueurs'),
(20805, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5513, 13218, 13222, 'Demie Finale - 1'),
(20806, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5513, 13214, 13226, 'Demie Finale - 2'),
(20807, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5513, NULL, NULL, 'Finale des perdants'),
(20808, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5513, NULL, NULL, 'Finale des vainqueurs'),
(20809, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5514, 13215, 13223, 'Demie Finale - 1'),
(20810, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5514, 13227, 13219, 'Demie Finale - 2'),
(20811, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5514, NULL, NULL, 'Finale des perdants'),
(20812, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5514, NULL, NULL, 'Finale des vainqueurs'),
(20813, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5515, 13228, 13220, 'Demie Finale - 1'),
(20814, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5515, 13216, 13224, 'Demie Finale - 2'),
(20815, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5515, NULL, NULL, 'Finale des perdants'),
(20816, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, 5515, NULL, NULL, 'Finale des vainqueurs');

-- --------------------------------------------------------

--
-- Structure de la table `usb_phases`
--

CREATE TABLE `usb_phases` (
  `id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `phase_precedente_id` int(11) DEFAULT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_suivante_id` int(11) DEFAULT NULL,
  `temps_match` int(11) DEFAULT NULL,
  `temps_entre_match` int(11) DEFAULT NULL,
  `param` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_phases`
--

INSERT INTO `usb_phases` (`id`, `type_id`, `phase_precedente_id`, `name`, `phase_suivante_id`, `temps_match`, `temps_entre_match`, `param`) VALUES
(1, 3, NULL, 'Phase 1 - U11', 2, NULL, NULL, '32'),
(2, 4, 1, 'Phase 2 - U11', 3, NULL, NULL, '32'),
(3, 5, 2, 'Demi et Finales U11', NULL, NULL, NULL, '32'),
(5, 3, NULL, 'Phase 1 - U13', 6, NULL, NULL, '24'),
(6, 4, 5, 'Phase 2 - U13', 7, NULL, NULL, '24'),
(7, 5, 6, 'Demie Finale - U13', NULL, NULL, NULL, '24');

-- --------------------------------------------------------

--
-- Structure de la table `usb_phases_categories`
--

CREATE TABLE `usb_phases_categories` (
  `category_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_phases_categories`
--

INSERT INTO `usb_phases_categories` (`category_id`, `phase_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 5),
(2, 6),
(2, 7);

-- --------------------------------------------------------

--
-- Structure de la table `usb_positions`
--

CREATE TABLE `usb_positions` (
  `id` int(11) NOT NULL,
  `poule_from_id` int(11) DEFAULT NULL,
  `poule_to_id` int(11) DEFAULT NULL,
  `rang` int(11) NOT NULL,
  `principal` tinyint(1) DEFAULT NULL,
  `phase_from_id` int(11) DEFAULT NULL,
  `id_string` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `int_param` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_positions`
--

INSERT INTO `usb_positions` (`id`, `poule_from_id`, `poule_to_id`, `rang`, `principal`, `phase_from_id`, `id_string`, `int_param`) VALUES
(13117, 5474, 5482, 1, NULL, 5, NULL, NULL),
(13118, 5474, 5483, 2, NULL, 5, NULL, NULL),
(13119, 5474, 5481, 3, NULL, 5, NULL, 1),
(13120, 5474, 5485, 4, NULL, 5, NULL, NULL),
(13121, 5475, 5483, 1, NULL, 5, NULL, NULL),
(13122, 5475, 5480, 2, NULL, 5, NULL, NULL),
(13123, 5475, 5482, 3, NULL, 5, NULL, 2),
(13124, 5475, 5485, 4, NULL, 5, NULL, NULL),
(13125, 5476, 5482, 1, NULL, 5, NULL, NULL),
(13126, 5476, 5480, 2, NULL, 5, NULL, NULL),
(13127, 5476, 5483, 3, NULL, 5, NULL, 3),
(13128, 5476, 5484, 4, NULL, 5, NULL, NULL),
(13129, 5477, 5480, 1, NULL, 5, NULL, NULL),
(13130, 5477, 5482, 2, NULL, 5, NULL, NULL),
(13131, 5477, 5481, 3, NULL, 5, NULL, 4),
(13132, 5477, 5484, 4, NULL, 5, NULL, NULL),
(13133, 5478, 5480, 1, NULL, 5, NULL, NULL),
(13134, 5478, 5481, 2, NULL, 5, NULL, NULL),
(13135, 5478, 5484, 3, NULL, 5, NULL, 5),
(13136, 5478, 5485, 4, NULL, 5, NULL, NULL),
(13137, 5479, 5481, 1, NULL, 5, NULL, NULL),
(13138, 5479, 5483, 2, NULL, 5, NULL, NULL),
(13139, 5479, 5484, 3, NULL, 5, NULL, 6),
(13140, 5479, 5485, 4, NULL, 5, NULL, NULL),
(13141, 5480, 5486, 1, 1, 6, NULL, NULL),
(13142, 5480, 5487, 2, 1, 6, NULL, NULL),
(13143, 5480, 5488, 3, 1, 6, NULL, NULL),
(13144, 5480, 5489, 4, 1, 6, NULL, NULL),
(13145, 5481, 5486, 1, 1, 6, NULL, NULL),
(13146, 5481, 5487, 2, 1, 6, NULL, NULL),
(13147, 5481, 5488, 3, 1, 6, NULL, NULL),
(13148, 5481, 5489, 4, 1, 6, NULL, NULL),
(13149, 5482, 5486, 1, 1, 6, NULL, NULL),
(13150, 5482, 5487, 2, 1, 6, NULL, NULL),
(13151, 5482, 5488, 3, 1, 6, NULL, NULL),
(13152, 5482, 5489, 4, 1, 6, NULL, NULL),
(13153, 5483, 5490, 1, 0, 6, NULL, NULL),
(13154, 5483, 5491, 2, 0, 6, NULL, NULL),
(13155, 5483, NULL, 3, 0, 6, NULL, NULL),
(13156, 5483, NULL, 4, 0, 6, NULL, NULL),
(13157, 5484, 5490, 1, 0, 6, NULL, NULL),
(13158, 5484, 5491, 2, 0, 6, NULL, NULL),
(13159, 5484, NULL, 3, 0, 6, NULL, NULL),
(13160, 5484, NULL, 4, 0, 6, NULL, NULL),
(13161, 5485, 5490, 1, 0, 6, NULL, NULL),
(13162, 5485, 5491, 2, 0, 6, NULL, NULL),
(13163, 5485, NULL, 3, 0, 6, NULL, NULL),
(13164, 5485, NULL, 4, 0, 6, NULL, NULL),
(13165, 5492, 5500, 1, NULL, 1, NULL, NULL),
(13166, 5492, 5501, 2, NULL, 1, NULL, NULL),
(13167, 5492, 5506, 3, NULL, 1, NULL, NULL),
(13168, 5492, 5507, 4, NULL, 1, NULL, NULL),
(13169, 5493, 5500, 1, NULL, 1, NULL, NULL),
(13170, 5493, 5503, 2, NULL, 1, NULL, NULL),
(13171, 5493, 5505, 3, NULL, 1, NULL, NULL),
(13172, 5493, 5507, 4, NULL, 1, NULL, NULL),
(13173, 5494, 5503, 1, NULL, 1, NULL, NULL),
(13174, 5494, 5501, 2, NULL, 1, NULL, NULL),
(13175, 5494, 5504, 3, NULL, 1, NULL, NULL),
(13176, 5494, 5506, 4, NULL, 1, NULL, NULL),
(13177, 5495, 5503, 1, NULL, 1, NULL, NULL),
(13178, 5495, 5502, 2, NULL, 1, NULL, NULL),
(13179, 5495, 5504, 3, NULL, 1, NULL, NULL),
(13180, 5495, 5507, 4, NULL, 1, NULL, NULL),
(13181, 5496, 5501, 1, NULL, 1, NULL, NULL),
(13182, 5496, 5500, 2, NULL, 1, NULL, NULL),
(13183, 5496, 5507, 3, NULL, 1, NULL, NULL),
(13184, 5496, 5506, 4, NULL, 1, NULL, NULL),
(13185, 5497, 5500, 1, NULL, 1, NULL, NULL),
(13186, 5497, 5502, 2, NULL, 1, NULL, NULL),
(13187, 5497, 5505, 3, NULL, 1, NULL, NULL),
(13188, 5497, 5504, 4, NULL, 1, NULL, NULL),
(13189, 5498, 5502, 1, NULL, 1, NULL, NULL),
(13190, 5498, 5503, 2, NULL, 1, NULL, NULL),
(13191, 5498, 5506, 3, NULL, 1, NULL, NULL),
(13192, 5498, 5505, 4, NULL, 1, NULL, NULL),
(13193, 5499, 5502, 1, NULL, 1, NULL, NULL),
(13194, 5499, 5501, 2, NULL, 1, NULL, NULL),
(13195, 5499, 5505, 3, NULL, 1, NULL, NULL),
(13196, 5499, 5504, 4, NULL, 1, NULL, NULL),
(13197, 5500, 5508, 1, 1, 2, NULL, NULL),
(13198, 5500, 5509, 2, 1, 2, NULL, NULL),
(13199, 5500, 5510, 3, 1, 2, NULL, NULL),
(13200, 5500, 5511, 4, 1, 2, NULL, NULL),
(13201, 5501, 5508, 1, 1, 2, NULL, NULL),
(13202, 5501, 5509, 2, 1, 2, NULL, NULL),
(13203, 5501, 5510, 3, 1, 2, NULL, NULL),
(13204, 5501, 5511, 4, 1, 2, NULL, NULL),
(13205, 5502, 5508, 1, 1, 2, NULL, NULL),
(13206, 5502, 5509, 2, 1, 2, NULL, NULL),
(13207, 5502, 5510, 3, 1, 2, NULL, NULL),
(13208, 5502, 5511, 4, 1, 2, NULL, NULL),
(13209, 5503, 5508, 1, 1, 2, NULL, NULL),
(13210, 5503, 5509, 2, 1, 2, NULL, NULL),
(13211, 5503, 5510, 3, 1, 2, NULL, NULL),
(13212, 5503, 5511, 4, 1, 2, NULL, NULL),
(13213, 5504, 5512, 1, 0, 2, NULL, NULL),
(13214, 5504, 5513, 2, 0, 2, NULL, NULL),
(13215, 5504, 5514, 3, 0, 2, NULL, NULL),
(13216, 5504, 5515, 4, 0, 2, NULL, NULL),
(13217, 5505, 5512, 1, 0, 2, NULL, NULL),
(13218, 5505, 5513, 2, 0, 2, NULL, NULL),
(13219, 5505, 5514, 3, 0, 2, NULL, NULL),
(13220, 5505, 5515, 4, 0, 2, NULL, NULL),
(13221, 5506, 5512, 1, 0, 2, NULL, NULL),
(13222, 5506, 5513, 2, 0, 2, NULL, NULL),
(13223, 5506, 5514, 3, 0, 2, NULL, NULL),
(13224, 5506, 5515, 4, 0, 2, NULL, NULL),
(13225, 5507, 5512, 1, 0, 2, NULL, NULL),
(13226, 5507, 5513, 2, 0, 2, NULL, NULL),
(13227, 5507, 5514, 3, 0, 2, NULL, NULL),
(13228, 5507, 5515, 4, 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usb_poules`
--

CREATE TABLE `usb_poules` (
  `id` int(11) NOT NULL,
  `phase_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `principal` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_poules`
--

INSERT INTO `usb_poules` (`id`, `phase_id`, `name`, `principal`) VALUES
(5474, 5, 'A', NULL),
(5475, 5, 'B', NULL),
(5476, 5, 'C', NULL),
(5477, 5, 'D', NULL),
(5478, 5, 'E', NULL),
(5479, 5, 'F', NULL),
(5480, 6, 'A - Principale', 1),
(5481, 6, 'B - Principale', 1),
(5482, 6, 'C - Principale', 1),
(5483, 6, 'D - Principale', 1),
(5484, 6, 'E - Consolante', 0),
(5485, 6, 'F - Consolante', 0),
(5486, 7, 'A - 1er des poules principales de phase 2', 1),
(5487, 7, 'B - 2ème des poules principales de phase 2', 1),
(5488, 7, 'C - 3ème des poules principales de phase 2', 1),
(5489, 7, 'D - 4ème des poules principales de phase 2', 1),
(5490, 7, 'E - 1er des poules consolantes de phase 2', 0),
(5491, 7, 'F - 2ème des poules consolantes de phase 2', 0),
(5492, 1, 'A', NULL),
(5493, 1, 'B', NULL),
(5494, 1, 'C', NULL),
(5495, 1, 'D', NULL),
(5496, 1, 'E', NULL),
(5497, 1, 'F', NULL),
(5498, 1, 'G', NULL),
(5499, 1, 'H', NULL),
(5500, 2, 'A - Principale', 1),
(5501, 2, 'B - Principale', 1),
(5502, 2, 'C - Principale', 1),
(5503, 2, 'D - Principale', 1),
(5504, 2, 'E - Consolante', 0),
(5505, 2, 'F - Consolante', 0),
(5506, 2, 'G - Consolante', 0),
(5507, 2, 'H - Consolante', 0),
(5508, 3, 'A - 1er des poules principales de phase 2', 1),
(5509, 3, 'B - 2ème des poules principales de phase 2', 1),
(5510, 3, 'C - 3ème des poules principales de phase 2', 1),
(5511, 3, 'D - 4ème des poules principales de phase 2', 1),
(5512, 3, 'E - 1er des poules consolantes de phase 2', 0),
(5513, 3, 'F - 2ème des poules consolantes de phase 2', 0),
(5514, 3, 'G - 3ème des poules consolantes de phase 2', 0),
(5515, 3, 'H - 4ème des poules consolantes de phase 2', 0);

-- --------------------------------------------------------

--
-- Structure de la table `usb_poules_teams`
--

CREATE TABLE `usb_poules_teams` (
  `poule_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_poules_teams`
--

INSERT INTO `usb_poules_teams` (`poule_id`, `team_id`) VALUES
(5474, 37),
(5474, 38),
(5474, 39),
(5474, 40),
(5475, 41),
(5475, 42),
(5475, 43),
(5475, 44),
(5476, 45),
(5476, 46),
(5476, 47),
(5476, 48),
(5477, 49),
(5477, 50),
(5477, 51),
(5477, 52),
(5478, 53),
(5478, 54),
(5478, 55),
(5478, 56),
(5479, 57),
(5479, 58),
(5479, 59),
(5479, 60),
(5492, 3),
(5492, 9),
(5492, 10),
(5492, 13),
(5493, 4),
(5493, 8),
(5493, 12),
(5493, 19),
(5494, 5),
(5494, 11),
(5494, 34),
(5494, 35),
(5495, 1),
(5495, 2),
(5495, 30),
(5495, 31),
(5496, 26),
(5496, 27),
(5496, 28),
(5496, 29),
(5497, 14),
(5497, 15),
(5497, 24),
(5497, 25),
(5498, 16),
(5498, 17),
(5498, 18),
(5498, 36),
(5499, 20),
(5499, 21),
(5499, 22),
(5499, 23);

-- --------------------------------------------------------

--
-- Structure de la table `usb_teams`
--

CREATE TABLE `usb_teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `rang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_teams`
--

INSERT INTO `usb_teams` (`id`, `name`, `category_id`, `rang`) VALUES
(1, 'Avrillé', 1, 1),
(2, 'Trélazé', 1, 2),
(3, 'Saint de Gemmes', 1, 3),
(4, 'Angers', 1, 4),
(5, 'Les ponts de cé', 1, 1),
(8, 'Toulouse', 1, 1),
(9, 'Saint Etienne', 1, 1),
(10, 'OM', 1, 4),
(11, 'OL', 1, 2),
(12, 'Barcelone', 1, 2),
(13, 'Quimper', 1, 2),
(14, 'Real', 1, NULL),
(15, 'Liverpool', 1, NULL),
(16, 'Aston Villa', 1, NULL),
(17, 'Monaco', 1, NULL),
(18, 'Frankfort', 1, NULL),
(19, 'A', 1, NULL),
(20, 'B', 1, NULL),
(21, 'C', 1, NULL),
(22, 'I', 1, NULL),
(23, 'D', 1, NULL),
(24, 'K', 1, NULL),
(25, 'E', 1, NULL),
(26, 'J', 1, NULL),
(27, 'F', 1, NULL),
(28, 'L', 1, NULL),
(29, 'G', 1, NULL),
(30, 'M', 1, NULL),
(31, 'H', 1, NULL),
(34, 'B', 1, NULL),
(35, 'J', 1, NULL),
(36, 'B', 1, NULL),
(37, 'AA', 2, NULL),
(38, 'BB', 2, NULL),
(39, 'CC', 2, NULL),
(40, 'DD', 2, NULL),
(41, 'EE', 2, NULL),
(42, 'FF', 2, NULL),
(43, 'GG', 2, NULL),
(44, 'HH', 2, NULL),
(45, 'II', 2, NULL),
(46, 'JJ', 2, NULL),
(47, 'KK', 2, NULL),
(48, 'LL', 2, NULL),
(49, 'MM', 2, NULL),
(50, 'NN', 2, NULL),
(51, 'OO', 2, NULL),
(52, 'PP', 2, NULL),
(53, 'QQ', 2, NULL),
(54, 'RR', 2, NULL),
(55, 'SS', 2, NULL),
(56, 'TT', 2, NULL),
(57, 'XX', 2, NULL),
(58, 'WW', 2, NULL),
(59, 'ZZ', 2, NULL),
(60, 'VV', 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usb_type_phase`
--

CREATE TABLE `usb_type_phase` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_by_poule` int(11) DEFAULT NULL,
  `format` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `param` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_type_phase`
--

INSERT INTO `usb_type_phase` (`id`, `name`, `team_by_poule`, `format`, `detail`, `param`) VALUES
(3, 'Normal (4)', 4, 'normal', '4 équipes par poule, toutes les équipes jouent les unes contre les autres', NULL),
(4, 'Principal - Consolante (4)', 4, 'principal-consolante', '4 équipes par poule, toutes les équipes jouent les unes contre les autres, principal consolante en fonction des résultats de la phase précédente\r\n', NULL),
(5, 'Demi-Finales Finales', 4, 'demifinalesfinales', 'Demi-finale + finale pour toutes les équipes (2 matchs de classement)', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usb_users`
--

CREATE TABLE `usb_users` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `usb_users`
--

INSERT INTO `usb_users` (`id`, `username`, `roles`, `password`) VALUES
(1, 'sylvainpellier', '[\"ROLE_ADMIN\"]', '$2y$13$3xWVcDOy9TfNdV3Qfh7qQuBRx5GfPxIFiUbCCIJW3yN1BTaUyWtQi'),
(2, 'pierre', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$sd4PRgsPCBtu9+iJ88AzJQ$+JS0TmVmLhnt2ZcWW2qfb0LTD4bbwfE4LE5TqVjTh6A');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `usb_fields`
--
ALTER TABLE `usb_fields`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `usb_groups`
--
ALTER TABLE `usb_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3C387FF18F5AAE64` (`phase_en_cours_id`);

--
-- Index pour la table `usb_meets`
--
ALTER TABLE `usb_meets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3E0AC2DBEA3FA723` (`team_a_id`),
  ADD KEY `IDX_3E0AC2DBF88A08CD` (`team_b_id`),
  ADD KEY `IDX_3E0AC2DB443707B0` (`field_id`),
  ADD KEY `IDX_3E0AC2DB99091188` (`phase_id`),
  ADD KEY `IDX_3E0AC2DBAEB42DE9` (`team_forfait_id`),
  ADD KEY `IDX_3E0AC2DB26596FD8` (`poule_id`),
  ADD KEY `IDX_3E0AC2DB28467D06` (`position_a_id`),
  ADD KEY `IDX_3E0AC2DB3AF3D2E8` (`position_b_id`);

--
-- Index pour la table `usb_phases`
--
ALTER TABLE `usb_phases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DB5C2F64C54C8C93` (`type_id`),
  ADD KEY `IDX_DB5C2F64BC3B2802` (`phase_suivante_id`),
  ADD KEY `IDX_DB5C2F649CECD98` (`phase_precedente_id`);

--
-- Index pour la table `usb_phases_categories`
--
ALTER TABLE `usb_phases_categories`
  ADD PRIMARY KEY (`category_id`,`phase_id`),
  ADD KEY `IDX_C8F2D3DF12469DE2` (`category_id`),
  ADD KEY `IDX_C8F2D3DF99091188` (`phase_id`);

--
-- Index pour la table `usb_positions`
--
ALTER TABLE `usb_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8B4AD9FFD4B8F0C4` (`poule_from_id`),
  ADD KEY `IDX_8B4AD9FF5A78EB94` (`poule_to_id`),
  ADD KEY `IDX_8B4AD9FF68AA7467` (`phase_from_id`);

--
-- Index pour la table `usb_poules`
--
ALTER TABLE `usb_poules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A17DD7F199091188` (`phase_id`);

--
-- Index pour la table `usb_poules_teams`
--
ALTER TABLE `usb_poules_teams`
  ADD PRIMARY KEY (`poule_id`,`team_id`),
  ADD KEY `IDX_B80EE82726596FD8` (`poule_id`),
  ADD KEY `IDX_B80EE827296CD8AE` (`team_id`);

--
-- Index pour la table `usb_teams`
--
ALTER TABLE `usb_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CFF336EC12469DE2` (`category_id`);

--
-- Index pour la table `usb_type_phase`
--
ALTER TABLE `usb_type_phase`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `usb_users`
--
ALTER TABLE `usb_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4DB2B15DF85E0677` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `usb_fields`
--
ALTER TABLE `usb_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `usb_groups`
--
ALTER TABLE `usb_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `usb_meets`
--
ALTER TABLE `usb_meets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20817;

--
-- AUTO_INCREMENT pour la table `usb_phases`
--
ALTER TABLE `usb_phases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `usb_positions`
--
ALTER TABLE `usb_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13229;

--
-- AUTO_INCREMENT pour la table `usb_poules`
--
ALTER TABLE `usb_poules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5516;

--
-- AUTO_INCREMENT pour la table `usb_teams`
--
ALTER TABLE `usb_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `usb_type_phase`
--
ALTER TABLE `usb_type_phase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `usb_users`
--
ALTER TABLE `usb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `usb_groups`
--
ALTER TABLE `usb_groups`
  ADD CONSTRAINT `FK_3C387FF18F5AAE64` FOREIGN KEY (`phase_en_cours_id`) REFERENCES `usb_phases` (`id`);

--
-- Contraintes pour la table `usb_meets`
--
ALTER TABLE `usb_meets`
  ADD CONSTRAINT `FK_3E0AC2DB26596FD8` FOREIGN KEY (`poule_id`) REFERENCES `usb_poules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3E0AC2DB28467D06` FOREIGN KEY (`position_a_id`) REFERENCES `usb_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3E0AC2DB3AF3D2E8` FOREIGN KEY (`position_b_id`) REFERENCES `usb_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3E0AC2DB443707B0` FOREIGN KEY (`field_id`) REFERENCES `usb_fields` (`id`),
  ADD CONSTRAINT `FK_3E0AC2DB99091188` FOREIGN KEY (`phase_id`) REFERENCES `usb_phases` (`id`),
  ADD CONSTRAINT `FK_3E0AC2DBAEB42DE9` FOREIGN KEY (`team_forfait_id`) REFERENCES `usb_teams` (`id`),
  ADD CONSTRAINT `FK_3E0AC2DBEA3FA723` FOREIGN KEY (`team_a_id`) REFERENCES `usb_teams` (`id`),
  ADD CONSTRAINT `FK_3E0AC2DBF88A08CD` FOREIGN KEY (`team_b_id`) REFERENCES `usb_teams` (`id`);

--
-- Contraintes pour la table `usb_phases`
--
ALTER TABLE `usb_phases`
  ADD CONSTRAINT `FK_DB5C2F649CECD98` FOREIGN KEY (`phase_precedente_id`) REFERENCES `usb_phases` (`id`),
  ADD CONSTRAINT `FK_DB5C2F64BC3B2802` FOREIGN KEY (`phase_suivante_id`) REFERENCES `usb_phases` (`id`),
  ADD CONSTRAINT `FK_DB5C2F64C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `usb_type_phase` (`id`);

--
-- Contraintes pour la table `usb_phases_categories`
--
ALTER TABLE `usb_phases_categories`
  ADD CONSTRAINT `FK_C8F2D3DF12469DE2` FOREIGN KEY (`category_id`) REFERENCES `usb_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C8F2D3DF99091188` FOREIGN KEY (`phase_id`) REFERENCES `usb_phases` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `usb_positions`
--
ALTER TABLE `usb_positions`
  ADD CONSTRAINT `FK_8B4AD9FF5A78EB94` FOREIGN KEY (`poule_to_id`) REFERENCES `usb_poules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8B4AD9FF68AA7467` FOREIGN KEY (`phase_from_id`) REFERENCES `usb_phases` (`id`),
  ADD CONSTRAINT `FK_8B4AD9FFD4B8F0C4` FOREIGN KEY (`poule_from_id`) REFERENCES `usb_poules` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `usb_poules`
--
ALTER TABLE `usb_poules`
  ADD CONSTRAINT `FK_A17DD7F199091188` FOREIGN KEY (`phase_id`) REFERENCES `usb_phases` (`id`);

--
-- Contraintes pour la table `usb_poules_teams`
--
ALTER TABLE `usb_poules_teams`
  ADD CONSTRAINT `FK_B80EE82726596FD8` FOREIGN KEY (`poule_id`) REFERENCES `usb_poules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B80EE827296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `usb_teams` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `usb_teams`
--
ALTER TABLE `usb_teams`
  ADD CONSTRAINT `FK_CFF336EC12469DE2` FOREIGN KEY (`category_id`) REFERENCES `usb_groups` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
