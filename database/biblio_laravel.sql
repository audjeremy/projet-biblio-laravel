-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 25 sep. 2025 à 03:12
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biblio_laravel`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `year`, `summary`, `price`, `discount`, `created_at`, `updated_at`) VALUES
(1, '1984', 'George Orwell', 'Dystopie', 1949, 'Un roman classique sur la surveillance et la liberté.', 14.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(2, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Conte', 1943, 'Un conte philosophique et poétique.', 9.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(3, 'L\'Étranger', 'Albert Camus', 'Roman', 1942, 'Une réflexion sur l’absurde et la condition humaine.', 12.50, 10.00, '2025-09-07 17:11:05', '2025-09-25 04:39:26'),
(4, 'Les Misérables', 'Victor Hugo', 'Roman historique', 1862, 'Un chef-d\'œuvre de la littérature française.', 18.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(5, 'Madame Bovary', 'Gustave Flaubert', 'Roman réaliste', 1857, 'Une critique de la société et du romantisme.', 11.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(6, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Fantasy', 1997, 'Le premier tome de la célèbre saga magique.', 16.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(7, 'Le Seigneur des anneaux', 'J.R.R. Tolkien', 'Fantasy', 1954, 'Une épopée héroïque dans la Terre du Milieu.', 25.00, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(8, 'L\'Alchimiste', 'Paulo Coelho', 'Roman initiatique', 1988, 'Un voyage spirituel à travers le désert.', 13.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(9, 'Da Vinci Code', 'Dan Brown', 'Thriller', 2003, 'Un thriller ésotérique et haletant.', 15.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(10, 'Millénium: Les hommes qui n\'aimaient pas les femmes', 'Stieg Larsson', 'Polar', 2005, 'Un polar suédois captivant.', 14.50, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(11, 'La Peste', 'Albert Camus', 'Roman', 1947, 'Une métaphore de la condition humaine et de la résistance.', 12.90, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(12, 'Notre-Dame de Paris', 'Victor Hugo', 'Roman gothique', 1831, 'Une fresque autour de la cathédrale et de ses personnages.', 17.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(13, 'Les Trois Mousquetaires', 'Alexandre Dumas', 'Roman d\'aventure', 1844, 'Les aventures d\'Athos, Porthos, Aramis et d’Artagnan.', 15.00, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(14, 'Voyage au centre de la Terre', 'Jules Verne', 'Science-fiction', 1864, 'Une expédition extraordinaire sous la Terre.', 13.50, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(15, 'Vingt mille lieues sous les mers', 'Jules Verne', 'Science-fiction', 1870, 'Les aventures du capitaine Nemo à bord du Nautilus.', 14.00, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(16, 'Fahrenheit 451', 'Ray Bradbury', 'Dystopie', 1953, 'Un monde où les livres sont interdits.', 12.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(17, 'La Ferme des animaux', 'George Orwell', 'Satire', 1945, 'Une fable politique sur le pouvoir et la corruption.', 10.99, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(18, 'Le Nom de la rose', 'Umberto Eco', 'Policier historique', 1980, 'Une enquête au sein d’une abbaye médiévale.', 16.50, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(19, 'Orgueil et Préjugés', 'Jane Austen', 'Roman', 1813, 'Une histoire d’amour et de critique sociale.', 11.50, 0.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(20, 'Crime et Châtiment', 'Fiodor Dostoïevski', 'Roman psychologique', 1866, 'Une plongée dans la conscience d’un meurtrier.', 25.00, 0.00, '2025-09-07 17:11:05', '2025-09-25 04:13:08'),
(21, 'Le Chien', 'Marie Johnson', 'Fantastique', 2012, 'Histoire d\'un chien.', 19.99, 0.00, '2025-09-09 15:48:18', '2025-09-09 15:48:18'),
(23, 'Le Dernier Voyage', 'Jean Tremblay', 'Roman', 2025, 'Roman québécois contemporain.', 24.99, 0.00, '2025-09-22 20:20:30', '2025-09-25 04:59:59'),
(24, 'Programmation Laravel', 'Marie Dubois', 'Éducatif', 2024, 'Guide pratique pour Laravel 12.', 39.99, 0.00, '2025-09-19 20:20:30', '2025-09-25 05:00:10'),
(25, 'Aventures Spatiales', 'Lucie Moreau', 'Science-Fiction', 2025, 'Science-fiction intergalactique.', 30.00, 10.00, '2025-09-15 20:20:30', '2025-09-25 05:00:21'),
(26, 'Hunger Games', 'Suzanne Collins', 'Jeune Adulte', 2008, 'Après que sa soeur a été sélectionnée pour participer aux violents \'Hunger Games\',\' Katniss Everdeen décide de se porter volontaire pour libérer sa soeur de son fardeau. La jeune femme et son comparse du District 12, Peeta, devront affronter 22 autres adolescents ayant entre 12 et 18 ans, jusqu\'à la mort.', 25.99, 15.00, '2025-09-25 04:52:12', '2025-09-25 04:52:36');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-jeremyaudette@icloud|127.0.0.1', 'i:1;', 1758762024),
('laravel-cache-jeremyaudette@icloud|127.0.0.1:timer', 'i:1758762024;', 1758762024);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, '2025-09-24 22:50:20', '2025-09-25 00:06:19');

-- --------------------------------------------------------

--
-- Structure de la table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'Johnny', 'johnny@mail.com', 'Heures', 'Bonjour! Quels sont vos heures d\'ouvertures?', 0, '2025-09-07 21:53:39', '2025-09-25 05:06:55'),
(2, 'Emmanuelle', 'm@mail.com', 'Manga', 'Avez-vous des mangas?', 0, '2025-09-08 18:06:22', '2025-09-08 18:06:22'),
(3, 'Marie', 'abc@def.com', 'Horreur', 'Bonjour! Avez vous des romans d\'horreur?', 0, '2025-09-08 22:27:39', '2025-09-08 22:27:39'),
(5, 'Paul', 'paul@mail.com', 'Emploi', 'Bonjour,\r\ncherchez vous des employés?', 1, '2025-09-25 01:20:56', '2025-09-25 05:06:29');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_02_120842_create_books_table', 1),
(5, '2025_09_02_133442_create_messages_table', 1),
(6, '2025_09_24_002634_add_role_to_users_table', 2),
(7, '2025_09_24_011129_create_carts_table', 2),
(8, '2025_09_24_011139_create_cart_items_table', 2),
(9, '2025_09_24_050636_create_orders_table', 2),
(10, '2025_09_24_050642_create_order_items_table', 2),
(11, '2025_09_24_105954_add_is_read_to_messages_table', 2),
(12, '2025_09_24_215059_add_discount_to_books_table', 3);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'CAD',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `provider` varchar(255) NOT NULL,
  `provider_session_id` varchar(255) DEFAULT NULL,
  `provider_payment_intent` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `currency`, `subtotal`, `discount`, `gst`, `qst`, `shipping`, `total`, `provider`, `provider_session_id`, `provider_payment_intent`, `status`, `meta`, `created_at`, `updated_at`) VALUES
(1, 1, 'CAD', 21.98, 0.00, 1.10, 2.19, 0.00, 25.27, 'paypal', '4NF75907N1906250L', '6NL70756WK572104C', 'paid', '{\"coupon\":null}', '2025-09-24 23:44:56', '2025-09-24 23:44:56'),
(2, 1, 'CAD', 93.94, 9.39, 4.23, 8.43, 0.00, 97.21, 'paypal', '33P60135US428954S', '5NT25656U12050355', 'paid', '{\"coupon\":{\"code\":\"PROMO10\",\"type\":\"percent\",\"value\":0.1,\"label\":\"-10%\"}}', '2025-09-25 00:06:19', '2025-09-25 00:06:19');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `title`, `author`, `quantity`, `unit_price`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 1, 17, 'La Ferme des animaux', 'George Orwell', 2, 10.99, 21.98, '2025-09-24 23:44:56', '2025-09-24 23:44:56'),
(2, 2, 1, '1984', 'George Orwell', 2, 14.99, 29.98, '2025-09-25 00:06:19', '2025-09-25 00:06:19'),
(3, 2, 9, 'Da Vinci Code', 'Dan Brown', 4, 15.99, 63.96, '2025-09-25 00:06:19', '2025-09-25 00:06:19');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Vb6OoeRXmvF1GyqOmHuYXBPeeOA4K9dYzRHFJp4u', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaTRHc09vMGlpV1FpcHdJdHhtTlBrbzhhcENzdURDSlBnbHZkVEp3ViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9ib29rcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1758762711);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jeremy', 'jeremyaudette@icloud.com', 'admin', NULL, '$2y$12$wuTd/Wq9AtU1bzs1DFsp0Odv75LaxKLu6TUfj1WZtLYgfIX8TOv6a', NULL, '2025-09-24 22:49:29', '2025-09-24 22:49:29');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Index pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_book_id_foreign` (`book_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_provider_index` (`provider`),
  ADD KEY `orders_provider_session_id_index` (`provider_session_id`),
  ADD KEY `orders_provider_payment_intent_index` (`provider_payment_intent`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_book_id_foreign` (`book_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
