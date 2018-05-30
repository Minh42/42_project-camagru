<?php

require_once __DIR__ . "/database.php";
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

/* CREATE DATABASE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS camagru";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

/* SELECT CAMAGRU DATABASE */ 
    $conn->query("use camagru");

/* CREATE USERS TABLE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS users (
    user_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(100) NOT NULL,
    activation_code varchar(255) NOT NULL,
    status INT DEFAULT 0 NOT NULL,
    token varchar(255),
    fb_id INT,
    twitter_id INT,
    profile_pic_url VARCHAR(255),
    alert_notification BOOLEAN DEFAULT TRUE NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table users created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

/* CREATE PHOTOS TABLE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS photos (
    photo_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    caption VARCHAR(300) NOT NULL,
    image_path VARCHAR(300) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table photos created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

/* CREATE COMMENTS TABLE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS comments (
    comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    comment VARCHAR(300) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table comments created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

/* CREATE PHOTOS_COMMENTS TABLE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS photos_comments (
    photo_id INT UNSIGNED NOT NULL,
    comment_id INT UNSIGNED NOT NULL
    )";
    
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table photos_comments created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

/* CREATE LIKES TABLE */
try {
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS likes (
    user_id BIGINT UNSIGNED NOT NULL,
    photo_id INT UNSIGNED NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table likes created successfully\n";
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>