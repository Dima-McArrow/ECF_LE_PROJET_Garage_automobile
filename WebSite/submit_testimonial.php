<?php

require './app_configs/db_config.php';

$name = $_POST['name'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];
$isApproved = 0;

try {
  $query = "INSERT INTO testimonials (name, rating, comment, is_approved) VALUES (:name, :rating, :comment, :is_approved)";
  $stmt = $pdo->prepare($query);

  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $comment);
  $stmt->bindParam(':is_approved', $isApproved);

  if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['error' => 'Submission failed.']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
?>