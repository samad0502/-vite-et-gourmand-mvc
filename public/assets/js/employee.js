/**
 * Ouvre la modale d'annulation de commande
 */
function openCancelModal(id, num) {
    const modalElement = document.getElementById('cancelModal');
    if (modalElement) {
        document.getElementById('cancelOrderId').value = id;
        document.getElementById('cancelOrderNum').innerText = num;
        new bootstrap.Modal(modalElement).show();
    }
}

/**
 * Gestion de l'onglet actif via l'URL (hash) au chargement
 */
document.addEventListener("DOMContentLoaded", function() {
    const hash = window.location.hash;
    if (hash) {
        const triggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }
});