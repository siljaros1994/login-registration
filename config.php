<?php
// Database credentials
$username = "root"; // Database username
$password = ""; // Database password
$host = "localhost"; // Database host
$dbname = "hugverk2"; // Database name

// Set PDO options
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    // Create a PDO instance (database connection)
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
} catch (PDOException $ex) {
    die("Failed to connect to the database: " . $ex->getMessage());
}

// Set default headers for JSON responses
header('Content-Type: application/json; charset=utf-8'); // Remove this line - it doesn't belong here
?>