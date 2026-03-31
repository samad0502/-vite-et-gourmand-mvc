document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('delivery_city');
    const distanceInput = document.getElementById('distance_km');
    const distanceContainer = document.getElementById('distance_container');
    
    const displayDelivery = document.getElementById('display_delivery');
    const displayTotal = document.getElementById('display_total');
    const finalTotalInput = document.getElementById('final_total_price');
    
    // On récupère le prix du menu calculé par PHP 
    const baseMenuPrice = parseFloat(document.getElementById('display_subtotal').innerText);

    function updatePrice() {
        let deliveryFees = 0;

        if (citySelect.value === 'outside') {
            distanceContainer.classList.remove('d-none');
            const km = parseFloat(distanceInput.value) || 0;
            // Règle : 5€ fixe + 0.59€ par km
            deliveryFees = 5 + (km * 0.59);
        } else {
            distanceContainer.classList.add('d-none');
            deliveryFees = 0;
        }

        const finalTotal = baseMenuPrice + deliveryFees;

        // Mise à jour de l'affichage
        displayDelivery.innerText = deliveryFees.toFixed(2);
        displayTotal.innerText = finalTotal.toFixed(2);
        
        // Mise à jour du champ caché pour la soumission SQL
        finalTotalInput.value = finalTotal.toFixed(2);
    }

    citySelect.addEventListener('change', updatePrice);
    distanceInput.addEventListener('input', updatePrice);
});