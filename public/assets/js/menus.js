/* Affiche les menus dans le conteneur html
*@param {Array} menus - liste des menus recuperes via l'API
*/


function renderMenus(menus) {

    const container = document.getElementById('menusContainer');
    if (!container) {
        console.error("ERREUR : Le conteneur #menusContainer est introuvable !");
        return;
    }

    // on vide le conteneur pour effacer les anciens resultats
    container.innerHTML = '';
    
    // on boucle sur chaque menu pour creer le html(cards menus)
         menus.forEach(menu => {
        
        const html = `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="assets/img/${menu.main_image}" class="card-img-top" alt="${menu.title}">
                    <div class="card-body">
                        <h5>${menu.title}</h5>
                        <p>${menu.price} €</p>
                    </div>
                </div>
            </div>`;
        container.innerHTML += html;
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