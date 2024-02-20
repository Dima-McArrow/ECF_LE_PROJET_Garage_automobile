<?php
// fetch_testimonials.php
require './app_configs/db_config.php';

try {
  // Ajustez la requête pour sélectionner uniquement les témoignages approuvés
  $stmt = $pdo->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC");
  $testimonials = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $testimonials[] = $row;
  }
  header('Content-Type: application/json');
  echo json_encode($testimonials);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
?>