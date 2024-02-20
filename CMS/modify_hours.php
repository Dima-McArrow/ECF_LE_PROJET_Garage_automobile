<?php
session_start();
require './app_configs/db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['day_of_week'] as $index => $day_of_week) {
    $state = $_POST['state'][$index] ?? 'ferme';


    $opening_time_am = $_POST['state_am'][$index] === 'ouvert' ? $_POST['opening_time_am'][$index] : null;
    $closing_time_am = $_POST['state_am'][$index] === 'ouvert' ? $_POST['closing_time_am'][$index] : null;
    $opening_time_pm = $_POST['state_pm'][$index] === 'ouvert' ? $_POST['opening_time_pm'][$index] : null;
    $closing_time_pm = $_POST['state_pm'][$index] === 'ouvert' ? $_POST['closing_time_pm'][$index] : null;

    // Update the database
    $query = "UPDATE garage_app.opening_hours SET 
                    opening_time_am = :opening_time_am, closing_time_am = :closing_time_am, 
                    opening_time_pm = :opening_time_pm, closing_time_pm = :closing_time_pm, 
                    state = :state
                  WHERE day_of_week = :day_of_week";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
      ':day_of_week' => $day_of_week,
      ':opening_time_am' => $opening_time_am,
      ':closing_time_am' => $closing_time_am,
      ':opening_time_pm' => $opening_time_pm,
      ':closing_time_pm' => $closing_time_pm,
      ':state' => $state,
    ]);
  }
  $message = '✔️ Horaires d\'ouverture mis à jour avec succès.';
}


$query = "SELECT * FROM garage_app.opening_hours ORDER BY day_of_week ASC";
$openingHours = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$dayNames = ['1' => 'Lundi', '2' => 'Mardi', '3' => 'Mercredi', '4' => 'Jeudi', '5' => 'Vendredi', '6' => 'Samedi', '7' => 'Dimanche'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion des horaires d'ouverture</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
  <style>
    .time-input {
      width: 80px;
    }

    .status-open {
      color: green;
    }

    .status-closed {
      color: red;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <h1 class="mb-4">Gestion des horaires d'ouverture</h1>
    <hr>
    <?php if ($message): ?>
      <div class="alert alert-success" role="alert">
        <?= $message ?>
      </div>
    <?php endif; ?>
    <form method="POST">
      <?php foreach ($openingHours as $index => $day): ?>
        <div class="mb-4">
          <h4>
            <?= $dayNames[$day['day_of_week']] ?>
          </h4>
          <input type="hidden" name="day_of_week[]" value="<?= $day['day_of_week'] ?>">
          <div class="mb-2">
            <label>Matin:</label>
            <select name="state_am[]" class="form-select">
              <option value="ouvert" <?= $day['opening_time_am'] ? 'selected' : '' ?>>Ouvert</option>
              <option value="ferme" <?= !$day['opening_time_am'] ? 'selected' : '' ?>>Fermé</option>
            </select>
            <input type="time" name="opening_time_am[]" value="<?= $day['opening_time_am'] ?>"
              class="time-input <?= $day['opening_time_am'] ? '' : 'd-none' ?>">
            à
            <input type="time" name="closing_time_am[]" value="<?= $day['closing_time_am'] ?>"
              class="time-input <?= $day['opening_time_am'] ? '' : 'd-none' ?>">
            <small class="<?= $day['opening_time_am'] ? 'status-open' : 'status-closed' ?>">
              <?= $day['opening_time_am'] ? 'Ouvert' : 'Fermé' ?>
            </small>
          </div>
          <div class="mb-2">
            <label>Après-midi:</label>
            <select name="state_pm[]" class="form-select">
              <option value="ouvert" <?= $day['opening_time_pm'] ? 'selected' : '' ?>>Ouvert</option>
              <option value="ferme" <?= !$day['opening_time_pm'] ? 'selected' : '' ?>>Fermé</option>
            </select>
            <input type="time" name="opening_time_pm[]" value="<?= $day['opening_time_pm'] ?>"
              class="time-input <?= $day['opening_time_pm'] ? '' : 'd-none' ?>">
            à
            <input type="time" name="closing_time_pm[]" value="<?= $day['closing_time_pm'] ?>"
              class="time-input <?= $day['opening_time_pm'] ? '' : 'd-none' ?>">
            <small class="<?= $day['opening_time_pm'] ? 'status-open' : 'status-closed' ?>">
              <?= $day['opening_time_pm'] ? 'Ouvert' : 'Fermé' ?>
            </small>
          </div>
          <div class="mb-2">
            <label>État pour toute la journée:</label>
            <select name="state[]" class="form-select">
              <option value="ouvert" <?= $day['state'] == 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
              <option value="ferme" <?= $day['state'] == 'ferme' ? 'selected' : '' ?>>Fermé</option>
            </select>
            <small class="<?= $day['state'] == 'ouvert' ? 'status-open' : 'status-closed' ?>">
              <?= $day['state'] == 'ouvert' ? 'Ouvert' : 'Fermé' ?>
            </small>
          </div>
        </div>
      <?php endforeach; ?>
      <button type="submit" class="btn btn-success">Sauvegarder les modifications</button>
    </form>
    <div class="row mt-5 mb-3">
      <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
  </div>
  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>