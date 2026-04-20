/**
 * Génère et affiche les cartes de menus dans le conteneur HTML
 * @param {Array} menus - Liste des menus récupérés via l'API
 */
function renderMenus(menus) {
    const container = document.getElementById('menusContainer');
    if (!container) return;

    // On vide le conteneur avant d'afficher les nouveaux résultats
    container.innerHTML = '';

    if (!menus || menus.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <p class="alert alert-info shadow-sm">Aucun menu ne correspond à vos critères.</p>
            </div>`;
        return;
    }

    menus.forEach(menu => {
        // Construction de la card menus
        container.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="public/assets/img/menus/${menu.main_image}" 
                         class="card-img-top" 
                         alt="${menu.title}">
                    
                    <div class="card-body">
                        <h5 class="card-title text-dark">${menu.title}</h5>
                        <p class="fw-bold text-primary fs-5">${menu.price} €</p>
                        
                        <div class="text-muted small mb-3">
                            <div><i class="bi bi-people"></i> Minimum ${menu.min_people} personnes</div>
                            <div><i class="bi bi-box-seam"></i> Quantité restante : ${menu.remaining_quantity}</div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 pb-3">
                        <a href="index.php?page=menu_detail&id=${menu.id}" class="btn btn-info w-100 text-white">
                            Voir le détail
                        </a>
                    </div>
                </div>
            </div>
        `;
    });
}

// charge tous les menus une fois la page prete
document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM prêt, envoi de la requête API...");
    fetch('index.php?page=api_menus')
        .then(response => response.json())
        .then(data => renderMenus(data))
        .catch(err => console.error("Échec de l'appel API :", err));
});