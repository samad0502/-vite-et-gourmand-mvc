<?php require_once ROOT . 'includes/header.php'; ?>
<div class="container my-5" style="max-width: 450px;">
    <div class="card p-4 shadow-sm border-0">
        <h2 class="text-center mb-4">Mot de passe oublié</h2>
        <?php if(isset($msg)): ?> <div class="alert alert-success"><?= $msg ?></div> <?php endif; ?>
        <?php if(isset($error)): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
        
        <form method="POST" action="index.php?page=forgot_password">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="mb-3">
                <label class="form-label fw-bold">Votre adresse e-mail</label>
                <input type="email" name="email" class="form-control" required placeholder="nom@exemple.com">
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">Envoyer le lien</button>
        </form>
    </div>
</div>
<?php require_once ROOT . 'includes/footer.php'; ?>