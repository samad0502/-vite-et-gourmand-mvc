const filterForm = document.getElementById('filtersForm');

filterForm.addEventListener('submit', function(e){
    e.preventDefault(); //empeche le rechargement de la page

    const priceMin = document.getElementById('priceMin').value;
    const priceMax = document.getElementById('priceMax').value;
    const minPeople = document.getElementById('minPeople').value;
    const theme = document.getElementById('theme').value;
    const diet = document.getElementById('diet').value;

    // appel de l'url du routeur MVC
    fetch(`index.php?page=api_menus&priceMin=${priceMin}&priceMax=${priceMax}$minPeople=${minPeople}&theme=${theme}&diet=${diet}`)
    .then(response => response.json())
    .then(data => {
        renderMenus(data); // fonction qui affichera les cards plutard dans menus.js
    })
    .catch(error => console.error('Erreur:', error));
    
});