<?php

require './app_configs/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collecter les données du formulaire
  $firstName = $_POST['firstName'] ?? '';
  $lastName = $_POST['lastName'] ?? '';
  $email = $_POST['email'] ?? '';
  $phoneNumber = $_POST['phone'] ?? '';
  $message = $_POST['message'] ?? '';

  // Valider les champs requis
  if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($message)) {
    echo $firstName . '<br>';
    echo $lastName . '<br>';
    echo $email . '<br>';
    echo $phoneNumber . '<br>';
    echo $message . '<br>';
    echo "Tous les champs sont requis!!!";
    exit;
  }

  try {
    // Préparer une instruction d'insertion
    $sql = "INSERT INTO garage_app.messages (first_name, last_name, email, phone_number, message) VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    // Bind les variables comme parameters
    $stmt->bindParam(1, $firstName);
    $stmt->bindParam(2, $lastName);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $phoneNumber);
    $stmt->bindParam(5, $message);

    // Essai d'executer l'instruction
    if ($stmt->execute()) {
      echo "Votre message est envoye!";
      header('Location: index.html');
    } else {
      echo "Une erreur s'est produit...";
    }
  } catch (PDOException $e) {
    die("ERROR: Pas de connection $sql. " . $e->getMessage());
  }

  // Fermeture de l'instruction
  unset($stmt);

  // Fermeture de la connection
  unset($pdo);
} else {
  echo "Uniquement les requettes POST prises en compte.";
}
?>