<?php 
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card-body p-4">
                    <h1 class="h3 mb-4 fw-bold">Connexion</h1>

                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php 
                 
                    if (isset($_GET['msg'])) {
                        switch ($_GET['msg']) {
                            case 'success_register':
                                echo '<div class="alert alert-success">Inscription réussie ! Connectez-vous.</div>';
                                break;
                            case 'password_updated':
                                echo '<div class="alert alert-success">Mot de passe mis à jour.</div>';
                                break;
                        }
                    }
                    ?>

                    <form action="index.php?page=auth_login" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" placeholder="votre@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input class="form-control" type="password" name="password" placeholder="********" required>
                        </div>
                        
                        <div class="mb-3 text-end">
                            <a href="index.php?page=forgot_password" class="small text-decoration-none">Mot de passe oublié ?</a>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">Se connecter</button>
                    </form>

                    <div class="mt-4 text-center">
                        <span class="text-muted">Pas encore de compte ?</span><br>
                        <a href="index.php?page=register" class="fw-bold text-decoration-none">Créer un compte ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . 'includes/footer.php'; ?>