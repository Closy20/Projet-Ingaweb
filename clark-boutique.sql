-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 05 juin 2024 à 13:33
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `clark-boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'android'),
(2, 'ordinateur'),
(3, 'test'),
(4, 'accessoire'),
(5, 'Personne '),
(6, 'Nestor');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `contenu` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `contenu`, `created_at`) VALUES
(3, 2, 1, 'dsgsz', '2024-06-03 21:03:55'),
(4, 2, 1, 'dsgsz', '2024-06-03 21:09:51'),
(11, 4, 3, 'Oui bien reçu ', '2024-06-04 00:43:49'),
(12, 3, 4, 'bbbj', '2024-06-04 02:31:20'),
(13, 4, 3, 'Tu dors déjà ?', '2024-06-04 03:01:14'),
(14, 3, 4, 'pas encore bro', '2024-06-04 03:01:58'),
(15, 3, 1, 'bbslkms', '2024-06-04 03:05:51'),
(16, 6, 4, 'hgiewd', '2024-06-04 03:37:26'),
(17, 1, 2, 'Bonjour ', '2024-06-04 11:45:27'),
(18, 2, 8, 'salot', '2024-06-04 11:49:52'),
(19, 9, 1, 'fffff', '2024-06-04 12:21:55'),
(20, 9, 1, 'df;djlkndd12144', '2024-06-04 21:06:31'),
(21, 9, 1, 'kojkflgk[ld\'g,;l,szf\';,x\',l;mh ;f\'vb,.;,rs\'fb \r\nrlfd,h\',m\'fx,;, sd;g,, \r\n\';lhl;\',mg.nvv m,.  v,meru yopjpkprm l,h,esd\r\nm h ,l ;,fj iwhtjkgvjhgemh;mnnjhtq4iupj8t8uthbchchj n uhyuhohkjjih40i095yonyojyl4rg\r\nesldjhotjjgk t', '2024-06-04 21:16:51'),
(22, 9, 1, 'Message : kojkflgk[ld\'g,;l,szf\';,x\',l;mh ;f\'vb,.;,rs\'fb rlfd,h\',m\'fx,;, sd;g,, \';lhl;\',mg.nvv m,. v,meru yopjpkprm l,h,esd m h ,l ;,fj iwhtjkgvjhgemh;mnnjhtq4iupj8t8uthbchchj n uhyuhohkjjih40i095yonyojyl4rg esldjhotjjgk t', '2024-06-04 21:17:55'),
(23, 9, 1, 'Message : kojkflgk[ld\'g,;l,szf\';,x\',l;mh ;f\'vb,.;,rs\'fb rlfd,h\',m\'fx,;, sd;g,, \';lhl;\',mg.nvv m,. v,meru yopjpkprm l,h,esd m h ,l ;,fj iwhtjkgvjhgemh;mnnjhtq4iupj8t8uthbchchj n uhyuhohkjjih40i095yonyojyl4rg esldjhotjjgk trter', '2024-06-04 21:18:19'),
(24, 9, 1, 'cfd', '2024-06-04 21:23:57'),
(25, 9, 1, 'bien', '2024-06-04 21:24:51'),
(26, 8, 9, 'Salut\r\n', '2024-06-04 21:37:00'),
(27, 8, 4, 'Humm', '2024-06-04 22:43:57');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `acheteur` varchar(255) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `titre`, `acheteur`, `categorie_id`, `image`, `description`, `prix`, `admin_id`) VALUES
(1, 'clccc', '', 1, 'img/cc.jpg', 'dsfaGSD', 12123, NULL),
(2, 'sparck', '', 1, 'img/cc.jpg', 'phone', 50000, NULL),
(3, 'dell', '', 2, 'img/ordi3.jpeg', 'machine', 200000, NULL),
(5, 'aaa', '', 4, 'img/kali.jpeg', 'jes', 1221, 3),
(6, 'Mbairamadji ', '', 5, 'img/IMG_20240218_110907_925.jpg', 'Personne ', 1000000, 4),
(7, 'Mbai', '', 5, 'img/IMG_20240528_121814_456.jpg', 'Hughbv', 258688, 4),
(8, 'hrbfk', '', 4, 'img/kali.jpeg', 'kjewnsd', 1122133, 6),
(9, 'Personne 100', '', 5, 'img/1712912052442.jpg', 'Une personne qui fait du bien partout au monde ', 2500, 8),
(10, 'test', '', 3, 'img/ordi3.jpeg', 'test description', 12223, 9),
(11, 'Jjek', '', 5, 'img/1676330474050-1.jpg', 'Ekj3', 2452, 9);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `acheteur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `password`, `avatar`) VALUES
(1, 'clark', 'clark@gmail.com', '$2y$10$S6zanjiP03kUR87jYi/VmuD8NnXx7CIeLzo7Fbf1WmgJO6pEeE4yC', ''),
(2, 'ad', 'admin1@gmail.com', '$2y$10$VFzXjZvtJRqP9BbbietJFeJIW/ylgoZVGv0CIKclIU.0opkfeJ9fW', ''),
(3, 'mbai', 'mbai@gmail.com', '$2y$10$b6kZmgmv9MWRU5oPzfp09O/Imq6dqzEk2HcEcqquYOIEIzDSGgqK6', ''),
(4, 'aaa', 'mbairamadjiclark57@gmail.com', '$2y$10$FqriKazzCweECukwmOdaWeuYex/7eS4bKI.DXuwa2K3vjxhqk8xiq', ''),
(5, 'bb', 'bb@gmail.com', '$2y$10$mQZMa4JB1POIC0M0.OyjH.AyBgvtLjIqRR390es/VewRfSvJS5rtC', ''),
(6, 'vvv', 'vvv@gmail.com', '$2y$10$p5OC0qIV01HH5UP9uhF2v.Xb1tiKqX54ZsU723XAFoAJnwToyOaIC', 'avatars/ordi3.jpeg'),
(8, 'Nestor', 'nestordjinfalbechindanne@gmail.com', '$2y$10$jnwm4oEeklMTpu20y3E6xOO9yhWc.UIbrtD3f4ECCurtBpp4WZ2ge', 'avatars/1706469247543.jpg'),
(9, 'mbairamadji', 'mbairamadjiclar57@gmail.com', '$2y$10$cFBcR.n67Toktrq6zQVk8O/VGU0xU5o9E1ocvkPTH1ve8P4H2ZRxa', 'avatars/IMG_20221209_115043_518_transcpr.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sender` (`sender_id`),
  ADD KEY `fk_receiver` (`receiver_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `fk_sender` FOREIGN KEY (`sender_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
