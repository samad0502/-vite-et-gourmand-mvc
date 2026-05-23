<?php require_once ROOT . 'includes/header.php'; ?>
<div class="container my-5" style="max-width: 450px;">
    <div class="card p-4 shadow-sm border-0">
        <h2 class="text-center mb-4">Nouveau mot de passe</h2>
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?page=reset_password&token=<?= htmlspecialchars($_GET['token']) ?>">
            <div class="mb-3">
                <label class="form-label fw-bold">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">Mettre à jour</button>
        </form>
    </div>
</div>
<?php require_once ROOT . 'includes/footer.php'; ?>