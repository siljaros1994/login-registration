<?php
// Our database credentials
$username = "root";
$password = "";
$host = "localhost";
$dbname = "hugverk2";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    // Here we create a PDO instance, which is a database connection
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    die("Failed to connect to the database: " . $ex->getMessage());
}

// Here we set default headers for JSON responses
header('Content-Type: application/json; charset=utf-8');
?>