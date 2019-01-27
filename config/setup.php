<?php

//CREATE DATABASE
function create_db(){
include ('database.php');
    try {
        $pdo = new PDO("mysql:host=$host;charset=$charset", $DB_USER, $DB_PASSWORD, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    try {
        $sql = "CREATE DATABASE IF NOT EXISTS `$db`";
        $pdo->exec($sql);
        print("Created db.\n");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $pdo = null;
}

//CREATE TABLE USER
function table_user(){
include ('connection.php');
    try {
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `login` VARCHAR(50) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `mail` VARCHAR(100) NOT NULL,
            `notif` VARCHAR(1) NOT NULL DEFAULT 'Y',
            `hash` VARCHAR(32) NOT NULL,
            `verified` VARCHAR(1) NOT NULL DEFAULT 'N'
          )";
        $pdo->exec($sql);
        print("Created user Table.\n");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $pdo = null;
}
//CREATE TABLE comments
function table_comments(){
    include('connection.php');
    try {
        $sql = "CREATE TABLE IF NOT EXISTS `comments` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_photo` INT NOT NULL,
            `comments` VARCHAR(250) NOT NULL,
            `login` VARCHAR(50) NOT NULL
          )";
        $pdo->exec($sql);
        print("Created `comments` Table.\n");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $pdo = null;
}

//CREATE TABLE photos
function table_photos(){
    include('connection.php');
    try {
        $sql = "CREATE TABLE IF NOT EXISTS `photos` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `photo` VARBINARY(25000) NOT NULL,
            `login` VARCHAR(250) NOT NULL,
            `date` DATE NOT NULL
          )";
        $pdo->exec($sql);
        print("Created `photos` Table.\n");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $pdo = null;
}

create_db();
table_user();
table_comments();
table_photos();
?>