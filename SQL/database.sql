-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mer. 07 mars 2018 à 08:44
-- Version du serveur :  5.6.38
-- Version de PHP :  7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  'final_project_php_website'
--
CREATE DATABASE IF NOT EXISTS 'final_project_php_website' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE 'final_project_php_website';

-- --------------------------------------------------------

--
  -- Structure de la table 'Products'
--

DROP TABLE IF EXISTS 'Products';
CREATE TABLE 'Products' (
  'productID' int(10) UNSIGNED NOT NULL,
  'productBrand' varchar(50) NOT NULL,
  'productModel' varchar(50) NOT NULL,
  'productMemory' int(11) DEFAULT NULL,
  'productStock' int(11) DEFAULT NULL,
  'productCost' float DEFAULT NULL,
  'productDescription' text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table 'Products'
--

INSERT INTO 'Products' ('productID', 'productBrand', 'productModel', 'productMemory', 'productStock', 'productCost', 'productDescription') VALUES
(1, 'Apple', 'iPhone 7', 32, 99, 899, 'Apple iPhone 7 with 32 GB of memory'),
(2, 'Samsung', 'Galaxy S7', 32, 98, 870, 'Samsung Galaxy S7 with 32 GB of memory'),
(3, 'BlackBerry', 'Priv', 32, 100, 599, 'BlackBerry Priv with 32 GB memory'),
(4, 'Sony', 'Xperia XZ', 32, 99, 699, 'Sony Xperia XZ with 32 GB memory'),
(5, 'Apple ', 'iPhone 7 Plus', 32, 10, 1049, 'Apple iPhone 7 Plus with 32 GB memory.'),
(6, 'Samsung', 'Galaxy S7 Edge', 32, 100, 999, 'Samsung Galaxy S7 Edge with 32 GB memory.'),
(7, 'Apple ', 'iPhone 6S', 16, 100, 699, 'Apple iPhone 6S with 16 GB memory.'),
(8, 'Apple', 'iPhone 7', 128, 100, 1069, 'Apple iPhone 7 with 128 GB memory.'),
(9, 'Apple', 'iPhone SE', 16, 100, 599, 'Apple iPhone SE with 16 GB memory.');

-- --------------------------------------------------------

--
-- Structure de la table 'UserInfo'
--

DROP TABLE IF EXISTS 'UserInfo';
CREATE TABLE 'UserInfo' (
  'UserID' int(10) UNSIGNED NOT NULL,
  'Username' varchar(50) NOT NULL,
  'UserFirstName' varchar(50) NOT NULL,
  'UserLastName' varchar(50) NOT NULL,
  'UserPhone' varchar(32) NOT NULL,
  'PasswordEncrypted' varchar(255) NOT NULL,
  'PasswordSalt' varbinary(100) NOT NULL,
  'EmailValidationString' varchar(32) NOT NULL,
  'UserType' varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table 'UserInfo'
--

INSERT INTO 'UserInfo' ('UserID', 'Username', 'UserFirstName', 'UserLastName', 'UserPhone', 'PasswordEncrypted', 'PasswordSalt', 'EmailValidationString', 'UserType') VALUES
(1, 'kous92@gmail.com', 'Koussaïla', 'BEN MAMAR', '+1 514-561-6346', '$2y$10$rJjSOpM2/Y83KDMmooT6geU9wGN6e9yWp3ipMgxE07sccZXwswqG.', 0xbc6d3bb90911fc23edac9513eed90bbb946882660fe9, 'TODO_EMAIL_VALIDATION', 'admin'),
(2, 'onlinestore.project.concordia@gmail.com', 'Admin', '', '+1 111-111-111', '$2y$10$WxgEae93HqEyUtvurRbxrenppVoze0hNa5kUImJGLxfNVH6snkgvG', 0xbc6d3bb90911fc23edac9513eed90bbb946882660fe9, 'TODO_EMAIL_VALIDATION', 'admin'),
(3, 'kb9@gmail.com', 'Karim', 'BENZEMA', '+33 6 24 70 93 34', '$2y$10$kZOrrgMvySOLj5VrzrRzTuHD.szPDuOPsGsiTwmaXdZTflzMGoySa', 0xbc6d3bb90911fc23edac9513eed90bbb946882660fe9, 'TODO_EMAIL_VALIDATION', 'regular'),
(4, 'johndoe@example.com', 'John', 'DOE', '+33 6 11 11 11 11', '$2y$10$kZOrrgMvySOLj5VrzrRzTuHD.szPDuOPsGsiTwmaXdZTflzMGoySa', 0xbc6d3bb90911fc23edac9513eed90bbb946882660fe9, 'TODO_EMAIL_VALIDATION', 'regular'),
(5, 'koussaila.ben.mamar@efrei.net', 'Koussaïla', 'BEN MAMAR', '+1 514-111-1111', '$2y$10$gzqWCtTpfuzxkxk8yR5hy.wiPceUiE6bC9GzvCh9qgnoL3uz7XeG6', 0xc5cbc894b9ce5144170ff49505374cf07ce9f06ead4a, 'TODO_EMAIL_VALIDATION', 'regular');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table 'Products'
--

ALTER TABLE 'Products'
  ADD PRIMARY KEY ('productID');

--
-- Index pour la table 'UserInfo'
--
ALTER TABLE 'UserInfo'
  ADD PRIMARY KEY ('UserID'),
  ADD UNIQUE KEY 'Username' ('Username');

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table 'Products'
--
ALTER TABLE 'Products'
  MODIFY 'productID' int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table 'UserInfo'
--
ALTER TABLE 'UserInfo'
  MODIFY 'UserID' int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;