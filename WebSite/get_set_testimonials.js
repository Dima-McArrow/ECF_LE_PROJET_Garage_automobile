document.addEventListener("DOMContentLoaded", function() {
  // Récupération initiale des commentaires
  fetchTestimonials();
  
  // Configurer un intervalle pour récupérer des témoignages toutes les 60 000 millisecondes (60 secondes)
  setInterval(fetchTestimonials, 60000);

  function fetchTestimonials() {
    fetch('fetch_testimonials.php')
      .then(response => response.json())
      .then(testimonials => {
        const container = document.getElementById('testimonialCards');
        container.innerHTML = '';
        testimonials.forEach(testimonial => {
          const cardHtml = `
            <div class="col-md-4 mb-4">
              <div class="card text-center">
                <div class="card-body">
                  <h5 class="card-title my_card-title">${testimonial.name}</h5>
                  <p class="card-text my_card-body">${testimonial.comment}</p>
                </div>
                <div class="card-footer my_card-body">Evaluation: ${testimonial.rating} / 5</div>
              </div>
            </div>`;
          container.innerHTML += cardHtml;
        });
      })
      .catch(error => console.error('Error:', error));
  }

  // Soumission de formulaire améliorée avec validation
  document.getElementById('testimonialForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validation Bootstrap personnalisée
    const form = this; // Référence au formulaire
    if (!form.checkValidity()) {
      e.stopPropagation();
      form.classList.add('was-validated');
      return; // Arrêter la soumission du formulaire si la validation échoue
    }
    
    // Procéder à la soumission du formulaire si la validation réussit
    const formData = new FormData(form);

    fetch('submit_testimonial.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        alert('✔️ Commentaire soumis avec succès!');
        form.reset();
        form.classList.remove('was-validated');
        
      } else if(data.error) {
        alert('Échec de la soumission: ' + data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Une erreur s\'est produite lors de la soumission de votre commentaire.');
    });
  });
});
