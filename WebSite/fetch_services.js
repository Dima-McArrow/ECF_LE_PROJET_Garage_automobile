document.addEventListener("DOMContentLoaded", function() {
  // Définir la fonction pour récupérer et afficher les services
  function fetchAndDisplayServices() {
    fetch('get_services.php')
      .then(response => response.json())
      .then(services => {
        const servicesContainer = document.getElementById('services')
        let cardsHtml = ''
        console.log(typeof(services))
        services.forEach(service => {
          cardsHtml += `
            <div class="card mt-1 mb-3" style="width: 18rem;">
              <div class="card-body">
                <h5 class="card-title text-center my_card-title">${service.name}</h5>
                <p class="card-text my_card-body">${service.description}</p>
              </div>
            </div>
          `
        })

        servicesContainer.innerHTML = cardsHtml
      })
      .catch(error => console.error('Error fetching services:', error))
  }

  // Récupérer et afficher les services immédiatement
  fetchAndDisplayServices()

  // Configurez l'intervalle pour récupérer et afficher automatiquement les services toutes les 30 minutes
  // 1800000 millisecondes = 30 minutes
  setInterval(fetchAndDisplayServices, 1800000)
})
