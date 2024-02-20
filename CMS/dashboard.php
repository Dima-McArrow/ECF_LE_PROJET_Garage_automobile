<?php
session_start();

// Vérifiez si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}

$user = $_SESSION['user']; // Obtenir les données de session utilisateur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <img class="main_logo mx-auto d-block mt-5 mb-5" style="width: 500px;"
      src="https://car-garage-bucket.s3.eu-west-3.amazonaws.com/logo.png" alt="logo Garage V. Parrot" />
    <hr>
    <h1 class="mb-4">Bonjour <strong>
        <?php echo htmlspecialchars($user['name']); ?>
      </strong>, vous etez connecte en temps que <strong>
        <?php echo htmlspecialchars($user['role']); ?>
      </strong>.</h1>
    <hr>
    <div class="list-group">
      <?php if ($user['role'] === 'admin'): ?>
        <a href="create_user.php" class="list-group-item list-group-item-action">Création d'utilisateurs</a>
        <a href="cars_dashboard.php" class="list-group-item list-group-item-action">Gestion des voitures d'occasion</a>
        <a href="messages_dashboard.php" class="list-group-item list-group-item-action">Gestion des messages</a>
        <a href="services_dashboard.php" class="list-group-item list-group-item-action">Gestion des services</a>
        <a href="testim_manage.php" class="list-group-item list-group-item-action">Gestion des commentaires</a>
        <a href="modify_hours.php" class="list-group-item list-group-item-action">Gestion d'horaire d'ouverture</a>
      <?php endif; ?>
      <?php if ($user['role'] === 'employee'): ?>
        <a href="testim_manage.php" class="list-group-item list-group-item-action">Gestion des commentaires</a>
        <a href="messages_dashboard.php" class="list-group-item list-group-item-action">Gestion des messages</a>
        <a href="cars_dashboard.php" class="list-group-item list-group-item-action">Gestion des voitures d'occasion</a>
      <?php endif; ?>
    </div>
    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>