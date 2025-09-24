-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 09 sep. 2025 à 13:30
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `year`, `summary`, `price`, `created_at`, `updated_at`) VALUES
(1, '1984', 'George Orwell', 'Dystopie', 1949, 'Un roman classique sur la surveillance et la liberté.', 14.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(2, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Conte', 1943, 'Un conte philosophique et poétique.', 9.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(3, 'L\'Étranger', 'Albert Camus', 'Roman', 1942, 'Une réflexion sur l’absurde et la condition humaine.', 12.50, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(4, 'Les Misérables', 'Victor Hugo', 'Roman historique', 1862, 'Un chef-d\'œuvre de la littérature française.', 18.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(5, 'Madame Bovary', 'Gustave Flaubert', 'Roman réaliste', 1857, 'Une critique de la société et du romantisme.', 11.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(6, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Fantasy', 1997, 'Le premier tome de la célèbre saga magique.', 16.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(7, 'Le Seigneur des anneaux', 'J.R.R. Tolkien', 'Fantasy', 1954, 'Une épopée héroïque dans la Terre du Milieu.', 25.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(8, 'L\'Alchimiste', 'Paulo Coelho', 'Roman initiatique', 1988, 'Un voyage spirituel à travers le désert.', 13.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(9, 'Da Vinci Code', 'Dan Brown', 'Thriller', 2003, 'Un thriller ésotérique et haletant.', 15.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(10, 'Millénium: Les hommes qui n\'aimaient pas les femmes', 'Stieg Larsson', 'Polar', 2005, 'Un polar suédois captivant.', 14.50, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(11, 'La Peste', 'Albert Camus', 'Roman', 1947, 'Une métaphore de la condition humaine et de la résistance.', 12.90, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(12, 'Notre-Dame de Paris', 'Victor Hugo', 'Roman gothique', 1831, 'Une fresque autour de la cathédrale et de ses personnages.', 17.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(13, 'Les Trois Mousquetaires', 'Alexandre Dumas', 'Roman d\'aventure', 1844, 'Les aventures d\'Athos, Porthos, Aramis et d’Artagnan.', 15.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(14, 'Voyage au centre de la Terre', 'Jules Verne', 'Science-fiction', 1864, 'Une expédition extraordinaire sous la Terre.', 13.50, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(15, 'Vingt mille lieues sous les mers', 'Jules Verne', 'Science-fiction', 1870, 'Les aventures du capitaine Nemo à bord du Nautilus.', 14.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(16, 'Fahrenheit 451', 'Ray Bradbury', 'Dystopie', 1953, 'Un monde où les livres sont interdits.', 12.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(17, 'La Ferme des animaux', 'George Orwell', 'Satire', 1945, 'Une fable politique sur le pouvoir et la corruption.', 10.99, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(18, 'Le Nom de la rose', 'Umberto Eco', 'Policier historique', 1980, 'Une enquête au sein d’une abbaye médiévale.', 16.50, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(19, 'Orgueil et Préjugés', 'Jane Austen', 'Roman', 1813, 'Une histoire d’amour et de critique sociale.', 11.50, '2025-09-07 17:11:05', '2025-09-07 17:11:05'),
(20, 'Crime et Châtiment', 'Fiodor Dostoïevski', 'Roman psychologique', 1866, 'Une plongée dans la conscience d’un meurtrier.', 18.00, '2025-09-07 17:11:05', '2025-09-07 17:11:05');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Johnny', 'johnny@mail.com', 'Heures', 'Bonjour! Quels sont vos heures d\'ouvertures?', '2025-09-07 21:53:39', '2025-09-07 21:53:39'),
(2, 'Emmanuelle', 'm@mail.com', 'Manga', 'Avez-vous des mangas?', '2025-09-08 18:06:22', '2025-09-08 18:06:22'),
(3, 'Marie', 'abc@def.com', 'Horreur', 'Bonjour! Avez vous des romans d\'horreur?', '2025-09-08 22:27:39', '2025-09-08 22:27:39');

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
(5, '2025_09_02_133442_create_messages_table', 1);

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
('mtUVKldTy7VndsaN0s13b7OUid19jM7xwgA4JvPT', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXpiN1dTUDY0WDRHQTJtelRIM3NDaW5XNlhlWjV1U0c1dGwwN2wzOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9ib29rcz92aWV3PWNhcmRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757416312),
('xf1sUPVjOFxs7cgqsgIkHNws6geGBitR1els4sBR', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWFjckxBWXlDdlY5TFhWSVFxeHVVRThlU2ZBdzVEa3MxQ0dmUGpMcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9ib29rcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757417394);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
