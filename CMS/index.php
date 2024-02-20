<?php
session_start();
require_once './app_configs/db_config.php';

// Vérifiez si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
  header('Location: dashboard.php');
  exit();
}

$error = '';

// Vérifiez si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Récupérer l'utilisateur de la base de données par email
  $query = "SELECT * FROM garage_app.users WHERE email = :email LIMIT 1";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Vérifier le mot de passe si l'utilisateur existe
  if ($user && password_verify($password, $user['password'])) {
    // Définir la variable de session pour identifier l'utilisateur
    $_SESSION['user'] = [
      'name' => $user['name'],
      'role' => $user['role']
    ];
    header('Location: dashboard.php');
    exit();
  } else {
    $error = "🚫 email ou mot de passe invalide"; // Afficher un message d'erreur si l'authentification échoue
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Login</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <img class="main_logo mx-auto d-block mt-5" style="width: 500px;" src="https://car-garage-bucket.s3.eu-west-3.amazonaws.com/logo.png"
    alt="logo Garage V. Parrot" />
  <div class="container text-center">
    <h1 class="mt-5 mb-5">Bienvenue sur le CMS du site web Garage V. Parrot</h1>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>
    <form action="index.php" method="post">
      <div class="mb-3 m-auto col-8 col-sm-6">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3 m-auto col-8 col-sm-6">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</body>
</html>