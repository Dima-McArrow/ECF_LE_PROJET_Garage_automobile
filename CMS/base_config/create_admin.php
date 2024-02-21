<?php
require_once '../app_config/db_config.php';

// Vérifier si l'utilisateur administrateur existe déjà
$query = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->execute(['email' => 'admin@admin.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur admin n'existe pas, le créer
if (!$user) {
  $name = 'default admin';
  $email = 'admin@admin.com';
  $password = password_hash('admin', PASSWORD_DEFAULT);

  // Insérer l'utilisateur admin dans la base de données
  $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'admin')";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

  echo 'Admin user created successfully.';
} else {
  echo 'Admin user already exists.';
}
?>