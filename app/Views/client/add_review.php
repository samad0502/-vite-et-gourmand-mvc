<div class="container my-5">
    <h2>Donner mon avis sur la commande <?= htmlspecialchars($order->getOrderNumber()) ?></h2>
    <form action="index.php?page=store_review" method="POST" class="shadow p-4 bg-white rounded">
        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">
        
        <div class="mb-3">
            <label class="form-label">Note (sur 5)</label>
            <select name="rating" class="form-select" required>
                <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (Très bien)</option>
                <option value="3">⭐⭐⭐ (Bien)</option>
                <option value="2">⭐⭐ (Moyen)</option>
                <option value="1">⭐ (Décevant)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Votre commentaire</label>
            <textarea name="comment" class="form-control" rows="4" placeholder="Qu'avez-vous pensé de votre repas ?" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Publier mon avis</button>
    </form>
</div>