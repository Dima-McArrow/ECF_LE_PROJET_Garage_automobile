document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour récupérer et afficher les voitures en fonction des filtres actuels
  function fetchAndDisplayCars() {
    // Construire la chaîne de requête à partir des valeurs du formulaire de filtre
    const formData = new FormData(document.getElementById("filterForm"));
    const searchParams = new URLSearchParams(formData).toString();

    fetch(`get_cars.php?${searchParams}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok: " + response.statusText);
        }
        return response.json();
      })
      .then((data) => {
        const carsContainer = document.getElementById("cars");
        carsContainer.classList.add("container");

        // Effacer le contenu existant et préparer une nouvelle ligne
        carsContainer.innerHTML = '<div class="row"></div>';
        const row = carsContainer.querySelector(".row");

        if (data.length === 0) {
          row.innerHTML = "<p>No cars found.</p>";
          return;
        }

        // Générer et ajouter du HTML pour chaque voiture
        data.forEach((car) => {
          const carHtml = `
            <div class="col-sm-4 mb-5 img-fluid">
              <div class="card text-center shadow">
                <img src="${car.image_url || "path/to/default/image.jpg"}" class="card-img-top" alt="Voiture" />
                <div class="card-body">
                  <h5 class="card-title card_title_font">${car.make} ${car.model}</h5>
                  <ul class="card-text card_body_font">
                    <li><b>Année:</b> <span class="car_year">${car.year}</span></li>
                    <li><b>Kilométrage:</b> <span class="car_mileage">${car.mileage}</span> <b> km</b></li><hr/>
                    <li><span class="prix"><strong>Prix:</strong></span>  <span class="car_price">${car.price}</span><span style="color: #262526;">&euro;</span></li>
                  </ul>
                  <a href="car_details.php?carId=${car.id}" class="btn btn_primary">Details</a>
                </div>
              </div>
            </div>
          `;
          row.innerHTML += carHtml;
        });
      })
      .catch((error) => console.error("Error fetching cars:", error.message));
  }

  fetchAndDisplayCars();

  setInterval(fetchAndDisplayCars, 60000);

  document.getElementById("applyFilters").addEventListener("click", function (event) {
    event.preventDefault();
    fetchAndDisplayCars();
  });

  document.getElementById("resetFilters").addEventListener("click", function () {
    // Réinitialise le formulaire. Cette ligne est facultative si le type de bouton est "reset" et peut être supprimée.
    document.getElementById("filterForm").reset();
    
    fetchAndDisplayCars();
  });
});
