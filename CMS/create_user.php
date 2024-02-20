<?php
session_start();
require_once './app_configs/db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

// Logique de création d'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash du password
  $role = $_POST['role'];

  // Insérer un nouvel utilisateur dans la base de données
  $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role]);
  echo "<script>alert('✔️ Utilisateur ' . $role . ' créé avec succès!<br>Nom: ' . $name);</script>";
  header('Location: dashboard.php');
  exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
  // Logique de suppression d'utilisateur
  $userId = $_POST['userId'];
  $query = "DELETE FROM users WHERE id = :userId";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['userId' => $userId]);
  echo "<script>alert('✔️ Utilisateur supprimé avec succès!');</script>";
  header('Location: dashboard.php');
  exit();
}

// Afficher tous les utilisateurs
$query = "SELECT * FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion des utilisateurs</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <h1 class="mb-4">Créer un utilisateur</h1>
    <hr>
    <form action="create_user.php" method="post" class="needs-validation" novalidate>
      <!-- Input pour différencier la création et la suppression -->
      <input type="hidden" name="action" value="create">
      <div class="mb-3">
        <label for="name" class="form-label">Nom complet:</label>
        <input type="text" class="form-control" id="name" name="name" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner le nom complet SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">e-mail:</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner l' e-mail SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner le MDP SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Rôle:</label>
        <select class="form-select" id="role" name="role" required>
          <option value="admin">Admin</option>
          <option value="employee">Employé</option>
        </select>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez choisir le rôle de l'utilisateur SVP
        </div>
      </div>
      <button type="submit" class="btn btn-success">Créer utilisateur</button>
    </form>
    <div class="row mt-5 mb-3">
      <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
    <h2 class="mb-4">Utilisateurs existants</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td>
              <?php echo htmlspecialchars($user['name']); ?>
            </td>
            <td>
              <?php echo htmlspecialchars($user['email']); ?>
            </td>
            <td>
              <?php echo htmlspecialchars($user['role']); ?>
            </td>
            <td>
              <form action="create_user.php" method="post">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                <button type="submit" class="btn btn-danger">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    (() => {
      "use strict";

      const forms = document.querySelectorAll(".needs-validation");

      Array.from(forms).forEach((form) => {
        form.addEventListener(
          "submit",
          (event) => {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
            }

            form.classList.add("was-validated");
          },
          false
        );
      });
    })();
  </script>
</body>

</html>