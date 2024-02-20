<?php
require './app_configs/db_config.php';

$carId = $_GET['carId'] ?? null;

if (!$carId) {
  echo "Car ID is required.";
  exit;
}

// Fetch des info voiture
$stmt = $pdo->prepare("SELECT used_cars.*, car_images.image_url FROM used_cars LEFT JOIN car_images ON used_cars.id = car_images.car_id WHERE used_cars.id = ?");
$stmt->execute([$carId]);
$carDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$carDetails) {
  echo "Car not found.";
  exit;
}

$car = $carDetails[0];

// Filtre les URLs des images
$images = array_column($carDetails, 'image_url');
$images = array_unique($images);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    Garage V. Parrot - <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>
  </title>
  <link rel="icon" type="image/x-icon" href="./img/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="./charte_graph/style.css" />
</head>
<body>
  <div class="container mt-3">
    <img class="main_logo mx-auto d-block mt-5 mb-5 mb-sm-3"
      src="https://car-garage-bucket.s3.eu-west-3.amazonaws.com/logo.png" alt="logo Garage V. Parrot" />
    <h1 class="page_voiture-titre">
      <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>
    </h1>
    <hr />
    <h2 class="id_voiture">ID du véhicule: <span class="id_voiture-nb">
        <?php echo htmlspecialchars($car['id']); ?>
      </span></h2>
    <div class="mt-3 page_voiture-text mb-5">
      <p><strong>Année:</strong>
        <?php echo htmlspecialchars($car['year']); ?>
      </p>
      <p><strong>Kilométrage:</strong>
        <?php echo htmlspecialchars($car['mileage']); ?> km
      </p>
      <p class="page_voiture-prix"><strong>Prix:</strong>
        <?php echo htmlspecialchars($car['price']); ?>&euro;
      </p>
      <p>
      <p><strong>Description:</strong>
        <?php echo nl2br(htmlspecialchars($car['description'])); ?>
      </p>
      <hr />
      <?php foreach ($images as $image_url): ?>
        <?php if ($image_url): // verifie si l'URL de l'image existe  ?>
          <div class="mb-3">
            <img src="<?php echo htmlspecialchars($image_url); ?>" class="img-fluid" alt="Car Image">
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="container" id="carInterest">
      <form id="contactForm" action="submit_message.php" method="post" class="mt-5 card_body_font needs-validation"
        novalidate>
        <div class="m-auto col-10 mb-3">
          <label for="lastName" class="form-label">Votre nom:</label>
          <input type="text" class="form-control" id="lastName" name="lastName" required>
          <div class="valid-feedback">
            Ok!
          </div>
          <div class="invalid-feedback">
            Veuillez renseigner votre nom SVP
          </div>
        </div>
        <div class="m-auto col-10 mb-3">
          <label for="firstName" class="form-label">Votre prénom:</label>
          <input type="text" class="form-control" id="firstName" name="firstName" required>
          <div class="valid-feedback">
            Ok!
          </div>
          <div class="invalid-feedback">
            Veuillez renseigner votre prénom SVP
          </div>
        </div>
        <div class="m-auto col-10 mb-3">
          <label for="email" class="form-label">Votre e-mail:</label>
          <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
          <div id="emailHelp" class="form-text">Nous nous engageons de ne jamais partager votre e-mail!</div>
          <div class="valid-feedback">
            Ok!
          </div>
          <div class="invalid-feedback">
            Veuillez renseigner votre e-mail au bon format SVP
          </div>
        </div>
        <div class="m-auto col-10 mb-3">
          <label for="phone" class="form-label">Votre téléphone:</label>
          <input type="tel" class="form-control" id="phone" name="phone" pattern="^[0-9]{10}$" required
            placeholder="0123456789">
          <div class="valid-feedback">
            Ok!
          </div>
          <div class="invalid-feedback">
            Veuillez renseigner votre numero de téléphone (10 chifres) SVP
          </div>
        </div>
        <div class="m-auto col-10">
          <label for="message" class="form-label">Votre message:</label>
          <textarea id="message" name="message" type="text" class="form-control" required
            readonly>Bonjour je suis intéressé par le véhicule <?php echo htmlspecialchars($car['id']); ?></textarea>
        </div>
        <div class="row mt-5">
          <button type="submit" class="btn btn_primary col-6 m-auto">Envoyer</button>
        </div>
      </form>
    </div>
    <div class="container mt-5">
      <div class="car_page-callus text-center">
        <p class="card_body_font">Ou applez-nous:</p>
        <a style="text-decoration: none; color:#262526" class="card_body_font tel" href="tel:+33768732599">
          <img src="./img/tel_bl.svg" alt="">07 68 73 25 99</a>
      </div>
    </div>
    <div class="container mt-5">
      <div class="row d-flex justify-content-center">
        <a href="javascript:history.back()" class="btn btn_back page_voiture-button">Retour</a>
      </div>
    </div>
  </div>
  <footer>
    <div class="container-fluid main_color_dark">
      <div class="row justify-content-center mt-5">
        <div class="col-11 col-sm-3 mt-5" id="openingHours">
          <ul id="opening_hours" class="card_body_font">
          </ul>
        </div>
        <div class="col-8 col-sm-3 mt-5">
          <p class="nav_font text-center">Contactez-nous</p>
          <div class="text-center text-light nav_font mt-3 mb-4">
            <a style="text-decoration: none; color:#f2f2f2" class="card_body_font tel" href="tel:+33768732599">
              <img src="./img/tel.svg" alt="">07 68 73 25 99</a>
          </div>
          <div class="text-center card_body_font mb-3 mt-2">
            <a style="color: #d94350; text-decoration: none;" class="card_body_font tel" href="contact.html">
              Notre formulaire de contact</a>
          </div>
        </div>
        <div class="col-8 col-sm-3 mt-5">
          <p class="nav_font text-center mb-3">Notre adresse</p>
          <p class="text-light card_body_font text-center">1 rue des Lilas d'Espagne</p>
          <p class="text-light card_body_font text-center">92400</p>
          <p class="text-light card_body_font text-center">Courbevoie</p>
          <div class="text-center card_body_font mb-3">
            <a style="color: #d94350; text-decoration: none;"
              href="https://www.google.com/maps/place/1+Rue+des+Lilas+d'Espagne,+92400+Courbevoie/@48.8972106,2.2312012,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6650023226b03:0x301bbe93f8b25661!8m2!3d48.8972072!4d2.2360721!16s%2Fg%2F11bw4n596w?entry=ttu"
              target="_blank">
              Vor sur Google Maps
            </a>
          </div>
        </div>
      </div>
      <hr style="color: #f2f2f2" />
      <div class="row">
        <div class="col text-center">
          <p class="text-light mt-1 mb-5 nav_font mt-3">
            &copy; 2024 <span class="logo_footer">Garage V. Parrot</span>.
            Tous droits réservés.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <script src="opening_hours.js"></script>
  <script>
    (() => {
      'use strict'

      const forms = document.querySelectorAll('.needs-validation')

      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
</body>

</html>