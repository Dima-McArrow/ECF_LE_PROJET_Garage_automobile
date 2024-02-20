<?php

require './app_configs/db_config.php';

try {
  // Préparer et exécuter la requête
  $stmt = $pdo->prepare("SELECT id, name, description FROM services");
  $stmt->execute();

  // Récupérer les résultats
  $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Renvoie les résultats au format JSON
  echo json_encode($services);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
