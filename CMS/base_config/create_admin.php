<?php
require_once '../app_config/db_config.php';

// Check if admin user already exists
$query = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->execute(['email' => 'vincent.parrot@mail.com']); // Change email as needed
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If admin user doesn't exist, create one
if (!$user) {
    $name = 'default admin';
    $email = 'admin@admin.com';
    $password = password_hash('admin', PASSWORD_DEFAULT);

    // Insert admin user into the database
    $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'admin')";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    echo 'Admin user created successfully.';
} else {
    echo 'Admin user already exists.';
}
?>
