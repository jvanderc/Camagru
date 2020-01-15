<?php

require('database.php');

try {
    $pdo = new PDO('mysql:host=localhost', $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'CREATE DATABASE IF NOT EXISTS camagru';
    
    $pdo->exec($sql);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'CREATE TABLE IF NOT EXISTS `users` (
    `id_user` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `login` VARCHAR(255) NOT NULL,
    `passwd` VARCHAR(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `creation_date` DATETIME NOT NULL,
    `confirmation` BOOLEAN DEFAULT 0 NOT NULL,
    `token` VARCHAR(255),
    `token_expiration_date` DATETIME NOT NULL
    )'; 

    $pdo->exec($sql);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'CREATE TABLE IF NOT EXISTS `photos` (
    `id_photo` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `login` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME,
    `photo` LONGBLOB NOT NULL 
    )';

    $pdo->exec($sql);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'CREATE TABLE IF NOT EXISTS `comments` (
    `id_comm` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_photo` INT UNSIGNED NOT NULL,
    `login` VARCHAR(255) NOT NULL,
    `comm` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME NOT NULL
    )'; 

    $pdo->exec($sql);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'CREATE TABLE IF NOT EXISTS `likes` (
    `id_like` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_photo` INT UNSIGNED NOT NULL,
    `login` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME NOT NULL
    )';

    $pdo->exec($sql);
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}