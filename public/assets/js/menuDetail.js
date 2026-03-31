/**
 * Gère la promo et l'ajout au panier via le contrôleur
 */
document.addEventListener('DOMContentLoaded', () => {
    const inputPeople = document.getElementById('number_people');
    const minPeopleSpan = document.getElementById('minPeople');
    const promoMsg = document.getElementById('promo-message');
    const orderForm = document.getElementById('orderForm');
    const orderBtn = document.getElementById('orderBtn');
    const loginModalElement = document.getElementById('loginModal');
    const loginForm = document.getElementById('loginForm');

   
    if (!inputPeople || !minPeopleSpan || !orderBtn) return;

    const minPeople = parseInt(minPeopleSpan.innerText) || 1;
    const threshold = minPeople + 5;

    // lOGIQUE de promo et stock
    function updatePromoMessage() {
        const current = parseInt(inputPeople.value) || 0;
        const stockRestant = parseInt(inputPeople.dataset.stock) || 0;
        
        if (promoMsg) {
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
    }

    inputPeople.addEventListener('input', updatePromoMessage);
    updatePromoMessage();

    // gestion de la connexion (modale)
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const errorBox = document.getElementById('loginError');

            const loginData = new FormData();
            loginData.append('email', email);
            loginData.append('password', password);

            // Envoi vers ton script de traitement de connexion
            fetch('auth/login_process.php', { 
                method: 'POST',
                body: loginData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Si connexion réussie, on recharge la page pour mettre à jour la session
                    window.location.reload(); 
                } else {
                    // Si erreur, on affiche le message dans la modale
                    errorBox.classList.remove('d-none');
                    errorBox.innerText = data.message || "Identifiants incorrects";
                }
            })
            .catch(err => console.error("Erreur connexion:", err));
        });
    }

     // gestion de l'ajout au panier
    if (orderForm) {
        orderForm.addEventListener('submit', event => {
            event.preventDefault();

            const isLogged = orderBtn.dataset.logged === '1';

            // si non connecté -> afficher la modal de connexion
            if (!isLogged) {
                if (loginModalElement) {
                    const modal = new bootstrap.Modal(loginModalElement);
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
                    // redirection vers le panier
                    window.location.href = 'index.php?page=cart';
                } else {
                    msgBox.innerHTML = `<div class="alert alert-danger mt-3">${result.message}</div>`;
                }
            })
            .catch(err => console.error("Erreur lors de l'ajout au panier :", err));
        });
    }
});