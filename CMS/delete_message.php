<?php
require './app_configs/db_config.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  try {
    $sql = "DELETE FROM garage_app.messages WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: dashboard.php");
  } catch (PDOException $e) {
    die("Could not delete message: " . $e->getMessage());
  }
} else {
  header("Location: dashboard.php");
}
?>