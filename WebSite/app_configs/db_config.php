<?php
// Database connection configuration
$host = '***'; // Database host
$dbname = '***'; // Database name
$username = '***'; // Database username
$password = '***'; // Database password

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>