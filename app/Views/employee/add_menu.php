<form action="index.php?page=save_menu" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label fw-bold">Nom du menu</label>
        <input type="text" name="title" class="form-control" placeholder="Ex: Buffet Campagnard" required>
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