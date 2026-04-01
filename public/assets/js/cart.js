document.addEventListener('DOMContentLoaded', function() {
    const cityInput = document.getElementById('cityInput');
    const distanceInput = document.getElementById('distanceInput');
    const finalTotalElement = document.getElementById('finalTotal');
    const hiddenInput = document.getElementById('final_total_price_input');
    const deliveryDisplay = document.getElementById('deliveryDisplay');
    const deliveryNote = document.getElementById('deliveryNote');

    
    if (!cityInput || !finalTotalElement) return;

    // Récupération du prix de base stocké en bdd
    const baseTotal = parseFloat(finalTotalElement.dataset.base);

    function calculateFees() {
        const city = cityInput.value.trim().toLowerCase();
        const distance = parseFloat(distanceInput.value) || 0;
        let fees = 0;

        if (city !== 'bordeaux' && city !== '') {
            fees = 5 + (0.59 * distance);
            if (deliveryNote) deliveryNote.innerText = "Forfait 5€ + 0.59€/km (Hors Bordeaux)";
        } else {
            if (deliveryNote) deliveryNote.innerText = "Gratuit (Secteur Bordeaux)";
        }

        const totalCalculated = baseTotal + fees;

        //  Mise à jour de l'affichage des frais
        if (deliveryDisplay) deliveryDisplay.innerText = fees.toFixed(2) + " €";

        // Mise à jour de l'affichage du total TTC
        finalTotalElement.innerText = totalCalculated.toFixed(2) + " €";

        // Mise à jour de l'input caché pour l'envoi vers PHP (BDD)
        if (hiddenInput) {
            hiddenInput.value = totalCalculated.toFixed(2);
        }
    }

    // Écouteurs d'événements
    cityInput.addEventListener('input', calculateFees);
    distanceInput.addEventListener('input', calculateFees);
    
   
    calculateFees();
});