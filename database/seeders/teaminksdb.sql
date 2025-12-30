-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 déc. 2025 à 04:15
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
-- Base de données : `teaminksdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--
-- Erreur de lecture de structure pour la table teaminksdb.cache : #1932 - Table &#039;teaminksdb.cache&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.cache : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`cache`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--
-- Erreur de lecture de structure pour la table teaminksdb.cache_locks : #1932 - Table &#039;teaminksdb.cache_locks&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.cache_locks : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`cache_locks`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--
-- Erreur de lecture de structure pour la table teaminksdb.failed_jobs : #1932 - Table &#039;teaminksdb.failed_jobs&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.failed_jobs : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`failed_jobs`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--
-- Erreur de lecture de structure pour la table teaminksdb.jobs : #1932 - Table &#039;teaminksdb.jobs&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.jobs : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`jobs`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--
-- Erreur de lecture de structure pour la table teaminksdb.job_batches : #1932 - Table &#039;teaminksdb.job_batches&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.job_batches : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`job_batches`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--
-- Erreur de lecture de structure pour la table teaminksdb.notifications : #1932 - Table &#039;teaminksdb.notifications&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.notifications : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`notifications`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--
-- Erreur de lecture de structure pour la table teaminksdb.password_reset_tokens : #1932 - Table &#039;teaminksdb.password_reset_tokens&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.password_reset_tokens : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`password_reset_tokens`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--
-- Erreur de lecture de structure pour la table teaminksdb.personal_access_tokens : #1932 - Table &#039;teaminksdb.personal_access_tokens&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.personal_access_tokens : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`personal_access_tokens`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--
-- Erreur de lecture de structure pour la table teaminksdb.posts : #1932 - Table &#039;teaminksdb.posts&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.posts : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`posts`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `post_comments`
--
-- Erreur de lecture de structure pour la table teaminksdb.post_comments : #1932 - Table &#039;teaminksdb.post_comments&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.post_comments : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`post_comments`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `post_likes`
--
-- Erreur de lecture de structure pour la table teaminksdb.post_likes : #1932 - Table &#039;teaminksdb.post_likes&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.post_likes : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`post_likes`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--
-- Erreur de lecture de structure pour la table teaminksdb.projects : #1932 - Table &#039;teaminksdb.projects&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.projects : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`projects`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--
-- Erreur de lecture de structure pour la table teaminksdb.sessions : #1932 - Table &#039;teaminksdb.sessions&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.sessions : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`sessions`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `subtasks`
--
-- Erreur de lecture de structure pour la table teaminksdb.subtasks : #1932 - Table &#039;teaminksdb.subtasks&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.subtasks : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`subtasks`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--
-- Erreur de lecture de structure pour la table teaminksdb.tasks : #1932 - Table &#039;teaminksdb.tasks&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.tasks : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`tasks`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--
-- Erreur de lecture de structure pour la table teaminksdb.teams : #1932 - Table &#039;teaminksdb.teams&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.teams : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`teams`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `team_members`
--
-- Erreur de lecture de structure pour la table teaminksdb.team_members : #1932 - Table &#039;teaminksdb.team_members&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.team_members : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`team_members`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `team_user`
--
-- Erreur de lecture de structure pour la table teaminksdb.team_user : #1932 - Table &#039;teaminksdb.team_user&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.team_user : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`team_user`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `users`
--
-- Erreur de lecture de structure pour la table teaminksdb.users : #1932 - Table &#039;teaminksdb.users&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table teaminksdb.users : #1064 - Erreur de syntaxe près de &#039;FROM `teaminksdb`.`users`&#039; à la ligne 1

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
