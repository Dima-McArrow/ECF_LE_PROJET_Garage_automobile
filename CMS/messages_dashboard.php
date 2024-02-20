<?php
session_start();
include './app_configs/db_config.php';

function fetchMessages($pdo)
{
  $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Delete Message
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $id = $_GET['id'];
  if (!empty($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: messages_dashboard.php");
    exit();
  } else {
    echo "<script>if (confirm('⚠️ Etes-vous sur de vouloir supprimer ce message?')) { window.location = 'messages_dashboard.php?action=delete&confirm=yes&id=" . $id . "'; } else { window.location = 'messages_dashboard.php'; }</script>";
    exit();
  }
}

$messages = fetchMessages($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Garage V. Parrot - Gestion des messages</title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Gestion des messages</h2>
    <hr>
    <div class="list-group my-3">
      <?php foreach ($messages as $message): ?>
        <div class="list-group-item">
          <h5 class="mb-1">
            <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
          </h5>
          <p class="mb-1"><a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
              <?php echo htmlspecialchars($message['email']); ?>
            </a></p>
          <p class="mb-1">
            <?php echo htmlspecialchars($message['phone_number']); ?>
          </p>
          <p class="mb-1">
            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
          </p>
          <small>Reçu le:
            <?php echo htmlspecialchars($message['created_at']); ?>
          </small>
          <div class="mt-2">
            <a href="?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-danger"
              onclick="return confirm('⚠️ Etes-vous sur de vouloir supprimer ce message?');">Supprimer</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="row mt-5 mb-3">
      <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
  </div>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>