/**
 * Gère la promo et l'ajout au panier via le contrôleur
 */

document.addEventListener('DOMContentLoaded', () => {
    const inputPeople = document.getElementById('number_people');
    const minPeopleSpan = document.getElementById('minPeople');
    const promoMsg = document.getElementById('promo-message');
    const orderForm = document.getElementById('orderForm');
    const orderBtn = document.getElementById('orderBtn');

    if (!inputPeople || !minPeopleSpan) return;

    const minPeople = parseInt(minPeopleSpan.innerText);
    const threshold = minPeople + 5;

    // lOGIQUE de promo et stock
    function updatePromoMessage() {
        const current = parseInt(inputPeople.value);
        const stockRestant = parseInt(inputPeople.dataset.stock);
        
        promoMsg.classList.remove('d-none');
        
        // vérification du stock
        if (current > stockRestant) {
            promoMsg.className = "mt-2 small shadow-sm p-2 rounded alert-danger border-danger text-danger fw-bold";
            promoMsg.innerHTML = `<i class="bi bi-exclamation-octagon"></i> Stock insuffisant (Max: ${stockRestant})`;
            orderBtn.disabled = true;
            return;
        } else {
            orderBtn.disabled = false;
        }

        // calcul de la promo
        if (current >= threshold) {
            promoMsg.className = "mt-2 small shadow-sm p-2 rounded alert-success border-success text-success fw-bold";
            promoMsg.innerHTML = '<i class="bi bi-patch-check-fill"></i> Félicitations ! Vous bénéficiez de -10% sur ce menu.';
        } else {
            const missing = threshold - current;
            promoMsg.className = "mt-2 small shadow-sm p-2 rounded alert-info border-info text-info";
            promoMsg.innerHTML = `<i class="bi bi-info-circle"></i> Ajoutez encore <strong>${missing} personnes</strong> pour obtenir 10% de réduction !`;
        }
    }

    inputPeople.addEventListener('input', updatePromoMessage);
    updatePromoMessage();

    // gestion de l'ajout au panier
    if (orderForm) {
        orderForm.addEventListener('submit', event => {
            event.preventDefault();

            const isLogged = orderBtn.dataset.logged === '1';
            

            // si non connecté -> afficher la modal de connexion
            if (!isLogged) {
                const modalEl = document.getElementById('loginModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
                return;
            }

            // préparation des données pour le contrôleur
            const data = new FormData();          
            data.append('menu_id', document.getElementById('menu_id').value);
            data.append('number_people', inputPeople.value);
            data.append('equipment_ready', document.getElementById('equipment_ready').value);

            // appel de la route définie dans index.php
            fetch('index.php?page=add_to_cart', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(result => {
                const msgBox = document.getElementById('orderMessage');
                if (result.success) {
                    msgBox.innerHTML = `<div class="alert alert-success mt-3 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i> ${result.message} 
                        <br><a href="index.php?page=cart" class="fw-bold text-decoration-none">→ Voir mon panier</a>
                    </div>`;
                    orderBtn.disabled = true;
                    orderBtn.innerText = "Produit ajouté";
                } else {
                    msgBox.innerHTML = `<div class="alert alert-danger mt-3">${result.message}</div>`;
                }
            })
            .catch(err => console.error("Erreur lors de l'ajout au panier :", err));
        });
    }
});