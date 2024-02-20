<?php
session_start();
require './vendor/autoload.php';
require './app_configs/db_config.php';
require './app_configs/s3conf.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}

try {
  $s3 = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'credentials' => [
      'key' => $IAM_KEY,
      'secret' => $IAM_SECRET,
    ],
  ]);
} catch (AwsException $e) {
  die("Error: " . $e->getMessage());
}

// *** feedback messages
$feedback = '';
if (isset($_SESSION['feedback']['success'])) {
  $feedback = $_SESSION['feedback']['success'];
  unset($_SESSION['feedback']['success']);
} elseif (isset($_SESSION['feedback']['error'])) {
  $feedback = $_SESSION['feedback']['error'];
  unset($_SESSION['feedback']['error']);
}
// ***

// Gérer la soumission du formulaire pour l'ajout d'une nouvelle voiture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make'])) {
  $checkSql = "SELECT COUNT(*) FROM used_cars WHERE make = ? AND model = ? AND year = ? AND mileage = ?";
  $checkStmt = $pdo->prepare($checkSql);
  $checkStmt->execute([$_POST['make'], $_POST['model'], $_POST['year'], $_POST['mileage']]);
  $exists = $checkStmt->fetchColumn() > 0;

  if ($exists) {
    $_SESSION['feedback']['error'] = "🚫 Une voiture avec ces spécifications existe déjà.";
  } else {
    $pdo->beginTransaction();
    try {
      $sql = "INSERT INTO used_cars (make, model, year, mileage, price, description) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$_POST['make'], $_POST['model'], $_POST['year'], $_POST['mileage'], $_POST['price'], $_POST['description']]);
      $carId = $pdo->lastInsertId();
      $folder = 'cars/';
      if (!empty($_FILES['car_images']['name'][0])) {
        foreach ($_FILES['car_images']['tmp_name'] as $index => $tmpFilePath) {
          $fileName = basename($_FILES['car_images']['name'][$index]);
          $newFileName = "{$folder}{$carId}/{$fileName}";
          $bucket = 'car-garage-bucket';
          $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $newFileName,
            'Body' => fopen($tmpFilePath, 'rb'),
            'ContentType' => mime_content_type($tmpFilePath),
            'ACL' => 'public-read',
            'ContentDisposition' => 'inline',
          ]);

          $imageSql = "INSERT INTO car_images (car_id, image_url) VALUES (?, ?)";
          $imageStmt = $pdo->prepare($imageSql);
          $imageStmt->execute([$carId, $result['ObjectURL']]);
        }
      }

      $pdo->commit();
      $_SESSION['feedback']['success'] = '✔️ Voiture ajoutée avec succès.';
    } catch (Exception $e) {
      $pdo->rollBack();
      $_SESSION['feedback']['error'] = "Error: " . $e->getMessage();
    }
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Fetch all cars from the database
$carQuery = "SELECT used_cars.id, make, model, description, year, mileage, price, MIN(car_images.image_url) as image_url
FROM used_cars
LEFT JOIN car_images ON used_cars.id = car_images.car_id
GROUP BY used_cars.id, make, model, description, year, mileage, price
ORDER BY used_cars.id DESC";
$cars = $pdo->query($carQuery)->fetchAll(PDO::FETCH_ASSOC);

// Handle car deletion
if (isset($_GET['action'], $_GET['car_id']) && $_GET['action'] === 'delete') {
  $carId = $_GET['car_id'];
  deleteCarImages($s3, $bucket, $folder . $carId . '/');
  $deleteSql = "DELETE FROM used_cars WHERE id = ?";
  $pdo->prepare($deleteSql)->execute([$carId]);
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}



// Function to delete car images from S3
function deleteCarImages($s3, $bucketName, $prefix)
{
  $bucketName = 'car-garage-bucket';
  $objects = $s3->listObjects([
    'Bucket' => $bucketName,
    'Prefix' => $prefix,
  ]);

  if (isset($objects['Contents']) && count($objects['Contents']) > 0) {
    $delete = [
      'Objects' => array_map(function ($object) {
        return ['Key' => $object['Key']];
      }, $objects['Contents'])
    ];
    $s3->deleteObjects([
      'Bucket' => $bucketName,
      'Delete' => $delete,
    ]);
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion des voitures d'occasion</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <?php if (!empty($feedback)): ?>
      <div class="alert alert-success">
        <?= htmlspecialchars($feedback) ?>
      </div>
    <?php endif; ?>
    <h1>Ajouter une nouvelle voiture</h1>
    <hr>
    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="make" class="form-label">Marque:</label>
        <input type="text" class="form-control" id="make" name="make" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner la marque SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="model" class="form-label">Modèle:</label>
        <input type="text" class="form-control" id="model" name="model" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner le modèle SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="year" class="form-label">Année:</label>
        <input type="number" class="form-control" id="year" name="year" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner l'année SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="mileage" class="form-label">Kilométrage:</label>
        <input type="number" class="form-control" id="mileage" name="mileage" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner le kilométrage SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Prix:</label>
        <input type="text" class="form-control" id="price" name="price" required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez renseigner le prix SVP
        </div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea class="form-control" id="description" name="description"></textarea>
      </div>
      <div class="mb-3">
        <label for="car_images" class="form-label">Image(s) de la voiture:</label>
        <input type="file" class="form-control" id="car_images" name="car_images[]" multiple required>
        <div class="valid-feedback">Ok!</div>
        <div class="invalid-feedback">
          Veuillez ajouter au moins une image du vehicule SVP
        </div>
      </div>
      <button type="submit" class="btn btn-success mt-3">Ajouter la voiture</button>
    </form>
    <div class="row mt-5 mb-3">
      <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
    <h2 class="mt-5">Liste des voitures</h2>
    <div class="row">
      <?php foreach ($cars as $car): ?>
        <div class="col-md-4">
          <div class="card mt-3 img-fluid mb-3">
            <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="voiture" class="card-img-top" />
            <div class="card-body">
              <h5 class="card-title">
                <?= htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']) ?>
              </h5>
              <p class="card-text">
                ID:
                <?= htmlspecialchars($car['id']) ?><br>
                Année:
                <?= htmlspecialchars($car['year']) ?><br>
                Kilométrage:
                <?= htmlspecialchars($car['mileage']) ?> km<br>
                Prix:
                <?= htmlspecialchars($car['price']) ?> €<br>
                Description:
                <?= htmlspecialchars($car['description']) ?>
              </p>
              <a href="?action=delete&car_id=<?= $car['id'] ?>" class="btn btn-danger"
                onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette voiture?');">Supprimer</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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