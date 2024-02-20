<?php
session_start();
require './app_configs/db_config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
  case 'approve':
    if (isset($_GET['id'])) {
      $testimonialId = $_GET['id'];
      $sql = "UPDATE testimonials SET is_approved = 1 WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$testimonialId]);
      header("Location: ?action=fetch");
    }
    break;

  case 'disapprove':
    if (isset($_GET['id'])) {
      $testimonialId = $_GET['id'];
      $sql = "UPDATE testimonials SET is_approved = 0 WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$testimonialId]);
      header("Location: ?action=fetch");
    }
    break;

  case 'delete':
    if (isset($_GET['id'])) {
      $testimonialId = $_GET['id'];
      $sql = "DELETE FROM testimonials WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$testimonialId]);
      header("Location: ?action=fetch");
    }
    break;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion des commentaires</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Gestion des commentaires</h2>
    <hr>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Comment</th>
            <th scope="col">Evaluation</th>
            <th scope="col">Approuvé</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<th scope='row'>{$row['id']}</th>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['comment']}</td>";
            echo "<td>{$row['rating']}</td>";
            echo "<td>" . ($row['is_approved'] ? 'Oui' : 'Non') . "</td>";
            echo "<td>";
            if (!$row['is_approved']) {
              echo "<a href='?action=approve&id={$row['id']}' class='btn btn-sm btn-success me-2'>Approuver</a>";
            } else {
              echo "<a href='?action=disapprove&id={$row['id']}' class='btn btn-sm btn-warning me-2'>Désapprouver</a>";
            }
            echo "<a href='?action=delete&id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('⚠️ Êtes-vous sûr?');\">Supprimer</a>";
            echo "</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="row">
    <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
  </div>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>