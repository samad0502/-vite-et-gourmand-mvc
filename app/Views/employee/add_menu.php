<form action="index.php?page=save_menu" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label fw-bold">Nom du menu</label>
        <input type="text" name="title" class="form-control" placeholder="Ex: Buffet Campagnard" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Prix (€)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Nombre min. personnes</label>
            <input type="number" name="min_people" class="form-control" value="1" required>
        </div>
    </div>

    <input type="hidden" name="theme_id" value="1">
    <input type="hidden" name="diet_id" value="1">
    <input type="hidden" name="remaining_quantity" value="100">

    <div class="mb-3">
        <label class="form-label fw-bold">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Image du plat</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>

    <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg">Créer le menu</button>
        <a href="index.php?page=employee_dashboard#menus-pane" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>