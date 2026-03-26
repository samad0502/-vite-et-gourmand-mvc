/* Affiche les menus dans le conteneur html
*@param {Array} menus - liste de smenus recuperes via l'API
*/

function renderMenus(menus) {
    const container = document.getElementById('menusContainer');

    // on vide le conteneur pour effacer les anciens resultats
    container.innerHTML = '';

    //si aucun menu ne correspond au filtres
    if(menus.length === 0){
        container.innerHTML = `
        <div class="col-12 text-center mt-5">
        <p class="alert alert-warning">Désolé, aucun menu ne correspond à vos critères.</p>
        </div>`;
        return;
    }
}
// on boucle sur chaque menu pour creer le html(cards menus)
menus.forEach(menu => {
    const cardHtml = `
    <div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                    <img src="${menu.image_url || 'assets/img/default-menu.jpg'}" 
                             class="card-img-top" 
                             alt="${menu.title}"
                             style="height: 200px; object-fit: cover;">
                        <span class="badge bg-info position-absolute top-0 end-0 m-3">
                            ${menu.price} €
                        </span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">${menu.title}</h5>
                        <p class="card-text text-muted small">${menu.description}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <button class="btn btn-outline-info w-100 btn-sm">
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += cardHtml;
    });
    // charge tous les menus une fois la page prete
    document.addEventListener('DOMContentLoaded', () => {
    fetch('index.php?page=api_menus')
        .then(response => response.json())
        .then(data => renderMenus(data));
});
