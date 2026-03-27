document.addEventListener('DOMContentLoaded', () => {
    const filtersForm = document.getElementById('filtersForm');
    
    if (filtersForm) {
        filtersForm.addEventListener('submit', function(event) {
             //empeche le rechargement de la page
            event.preventDefault(); 
            
            console.log("Filtrage en cours...");
            triggerFilter();
        });
    }
  


function triggerFilter() {
    const params = new URLSearchParams({
        page: 'api_menus',
        priceMin: document.getElementById('priceMin').value,
        priceMax: document.getElementById('priceMax').value,
        minPeople: document.getElementById('minPeople').value,
        theme: document.getElementById('theme').value,
        diet: document.getElementById('diet').value
    });

    // appel de l'url du routeur MVC
    fetch(`index.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            renderMenus(data); // fonction qui affichera les cards plutard dans menus.js
        })
        .catch(error => console.error("Erreur de filtrage :", error));
}})