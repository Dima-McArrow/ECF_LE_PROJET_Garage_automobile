<?php
require './app_configs/db_config.php';

// Initialiser les variables de filtre à partir des paramètres de requête, en considérant les chaînes vides comme nulles
$minPrice = isset($_GET['minPrice']) && $_GET['minPrice'] !== '' ? (float) $_GET['minPrice'] : null;
$maxPrice = isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '' ? (float) $_GET['maxPrice'] : null;
$minMileage = isset($_GET['minMileage']) && $_GET['minMileage'] !== '' ? (int) $_GET['minMileage'] : null;
$maxMileage = isset($_GET['maxMileage']) && $_GET['maxMileage'] !== '' ? (int) $_GET['maxMileage'] : null;
$minYear = isset($_GET['minYear']) && $_GET['minYear'] !== '' ? (int) $_GET['minYear'] : null;
$maxYear = isset($_GET['maxYear']) && $_GET['maxYear'] !== '' ? (int) $_GET['maxYear'] : null;

$query = "SELECT used_cars.id, make, model, description, year, mileage, price, MIN(car_images.image_url) as image_url
          FROM used_cars
          LEFT JOIN car_images ON used_cars.id = car_images.car_id
          WHERE 1=1";

$params = []; // Paramètres de la requête

// Ajouter des conditions basées sur des filtres
if ($minPrice !== null) {
  $query .= " AND price >= :minPrice";
  $params[':minPrice'] = $minPrice;
}
if ($maxPrice !== null) {
  $query .= " AND price <= :maxPrice";
  $params[':maxPrice'] = $maxPrice;
}
if ($minMileage !== null) {
  $query .= " AND mileage >= :minMileage";
  $params[':minMileage'] = $minMileage;
}
if ($maxMileage !== null) {
  $query .= " AND mileage <= :maxMileage";
  $params[':maxMileage'] = $maxMileage;
}
if ($minYear !== null) {
  $query .= " AND year >= :minYear";
  $params[':minYear'] = $minYear;
}
if ($maxYear !== null) {
  $query .= " AND year <= :maxYear";
  $params[':maxYear'] = $maxYear;
}

$query .= " GROUP BY used_cars.id ORDER BY used_cars.created_at DESC";

header('Content-Type: application/json');

try {
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Vérifiez si des voitures sont trouvées
  if ($cars) {
    echo json_encode($cars);
  } else {
    // Aucune voiture trouvée
    echo json_encode(['message' => 'No cars found.']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
?>