document.addEventListener('DOMContentLoaded', function() {
    const cityInput = document.getElementById('cityInput');
    const distanceInput = document.getElementById('distanceInput');
    const finalTotalElement = document.getElementById('finalTotal');
    const hiddenInput = document.getElementById('final_total_price_input');
    const deliveryDisplay = document.getElementById('deliveryDisplay');
    const deliveryNote = document.getElementById('deliveryNote');
    
    // On récupère le champ adresse (textarea) pour le géocodage
    const addressInput = document.querySelector('textarea[name="address"]');

    if (!cityInput || !finalTotalElement) return;

    const baseTotal = parseFloat(finalTotalElement.dataset.base);
    const BORDEAUX_COORDS = { lat: 44.837789, lon: -0.57918 }; // Centre de Bordeaux

    // FONCTION DE CALCUL DES FRAIS 
    function calculateFees() {
        const city = cityInput.value.trim().toLowerCase();
        const distance = parseFloat(distanceInput.value) || 0;
        let fees = 0;

        if (city !== 'bordeaux' && city !== '') {
            fees = 5 + (0.59 * distance);
            if (deliveryNote) deliveryNote.innerText = "Forfait 5€ + 0.59€/km (Hors Bordeaux)";
        } else {
            fees = 0;
            if (deliveryNote) deliveryNote.innerText = "Gratuit (Secteur Bordeaux)";
        }

        const totalCalculated = baseTotal + fees;
        if (deliveryDisplay) deliveryDisplay.innerText = fees.toFixed(2) + " €";
        finalTotalElement.innerText = totalCalculated.toFixed(2) + " €";
        if (hiddenInput) hiddenInput.value = totalCalculated.toFixed(2);
    }

    // FONCTION GÉOCODAGE ET DISTANCE 
    async function getDistanceOSM() {
        const address = addressInput.value.trim();
        const city = cityInput.value.trim();
        
        if (city.toLowerCase() === 'bordeaux') {
            distanceInput.value = 0;
            calculateFees();
            return;
        }

        if (address === "" || city === "") return;

        const fullAddress = `${address}, ${city}, France`;
        
        if (deliveryNote) deliveryNote.innerText = "Calcul de la distance en cours...";

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fullAddress)}`);
            const data = await response.json();

            if (data && data.length > 0) {
                const destLat = parseFloat(data[0].lat);
                const destLon = parseFloat(data[0].lon);

                // Calcul distance Haversine
                const dist = calculateHaversine(BORDEAUX_COORDS.lat, BORDEAUX_COORDS.lon, destLat, destLon);
                
                distanceInput.value = dist.toFixed(2);
                calculateFees();
            } else {
                if (deliveryNote) deliveryNote.innerText = "Adresse introuvable pour le calcul auto.";
            }
        } catch (error) {
            console.error("Erreur OSM:", error);
        }
    }

    // Formule mathématique de Haversine
    function calculateHaversine(lat1, lon1, lat2, lon2) {
        const R = 6371; 
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; 
    }

    
    // Si l'utilisateur change manuellement le KM, on recalcule le prix
    distanceInput.addEventListener('input', calculateFees);
    
    // On déclenche le calcul OSM quand on quitte le champ ville ou adresse
    cityInput.addEventListener('blur', getDistanceOSM);
    addressInput.addEventListener('blur', getDistanceOSM);

    calculateFees(); 
});