<form action="index.php?page=update_client_order" method="POST">
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Nombre de personnes (Min: <?= $order['min_people'] ?>)</label>
        <input type="number" name="number_people" class="form-control" 
               value="<?= $order['number_people'] ?>" min="<?= $order['min_people'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Adresse de livraison</label>
        <textarea name="delivery_address" class="form-control" required><?= htmlspecialchars($order['delivery_address']) ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="delivery_date" class="form-control" value="<?= $order['delivery_date'] ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Heure</label>
            <input type="time" name="delivery_time" class="form-control" value="<?= $order['delivery_time'] ?>" required>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="index.php?page=orders" class="btn btn-outline-secondary">Retour</a>
        <button type="submit" class="btn btn-warning text-dark fw-bold">Enregistrer les modifications</button>
    </div>
</form>