<?php
session_start();
include './app_configs/db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

function fetchServices($pdo)
{
  $stmt = $pdo->query("SELECT * FROM services");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter ou mettre à jour un service
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'] ?? null;
  $name = $_POST['name'];
  $description = $_POST['description'];

  if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $sql = "INSERT INTO services (name, description) VALUES (?, ?)";
  } elseif (isset($_POST['action']) && $_POST['action'] == 'edit' && $id) {
    $sql = "UPDATE services SET name = ?, description = ? WHERE id = ?";
  }

  $stmt = $pdo->prepare($sql);
  $id ? $stmt->execute([$name, $description, $id]) : $stmt->execute([$name, $description]);
  header("Location: services_dashboard.php");
  exit();
}

// Supprimer le service
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $id = $_GET['id'];
  if (!empty($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: services_dashboard.php");
    exit();
  } else {
    echo "<script>if (confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce service?')) { window.location = 'services_dashboard.php?action=delete&confirm=yes&id=" . $id . "'; } else { window.location = 'services_dashboard.php'; }</script>";
    exit();
  }
}

$services = fetchServices($pdo);
$editingService = null;

if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
  $stmt->execute([$id]);
  $editingService = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des services</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5 mb-5">
    <h2>Gestion des services</h2>
    <hr>
    <div class="list-group my-3">
      <?php foreach ($services as $service): ?>
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-bold">
              <?php echo htmlspecialchars($service['name']); ?>
            </div>
            <?php echo htmlspecialchars($service['description']); ?>
          </div>
          <div>
            <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
            <a href="?action=delete&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger"
              onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce service?');">Supprimer</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if ($editingService): ?>
      <h3>Modifier les services</h3>
      <form action="services_dashboard.php" method="post">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $editingService['id']; ?>">
        <div class="mb-3">
          <label for="name" class="form-label">Nom du service:</label>
          <input type="text" class="form-control" id="name" name="name"
            value="<?php echo htmlspecialchars($editingService['name']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description:</label>
          <textarea class="form-control" id="description" name="description"
            required><?php echo htmlspecialchars($editingService['description']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Modifier Service</button>
      </form>
    <?php else: ?>
      <h3>Ajouter un nouveau service</h3>
      <form action="services_dashboard.php" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
          <label for="name" class="form-label">Nom du service:</label>
          <input type="text" class="form-control" id="name" name="name" required>
          <div class="valid-feedback">Ok!</div>
          <div class="invalid-feedback">
            Veuillez renseigner le nom du service SVP
          </div>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description:</label>
          <textarea class="form-control" id="description" name="description" required></textarea>
          <div class="valid-feedback">Ok!</div>
          <div class="invalid-feedback">
            Veuillez renseigner la description SVP
          </div>
        </div>
        <button type="submit" class="btn btn-success">Ajouter Service</button>
      </form>
    <?php endif; ?>
    <div class="row mt-5">
      <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
  </div>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
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