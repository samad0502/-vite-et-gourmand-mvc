<div class="card-body p-4">
    <form action="index.php?page=update_menu" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="menu_id" value="<?= $menu[0]['id'] ?>">

        <div class="mb-3">
            <label class="form-label fw-bold">Nom du menu</label>
            <input type="text" name="title" value="<?= htmlspecialchars($menu[0]['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Prix (€)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $menu['price'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($menu['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Image actuelle</label>
            <div class="mb-2">
                <?php if (!empty($menu['image'])): ?>
                    <img src="assets/img/menus/<?= htmlspecialchars($menu['image']) ?>" width="150" class="img-thumbnail">
                <?php endif; ?>
            </div>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="index.php?page=employee_dashboard#menus-pane" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
    </form>
</div>