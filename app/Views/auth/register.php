<?php
 require_once ROOT . 'includes/header.php';
 require_once ROOT . 'includes/navbar.php';
  ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-bold mb-4 text-center">Créer mon compte</h2>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger shadow-sm">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=auth_register" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Prénom</label>
                                <input type="text" name="firstname" class="form-control" placeholder="Jean" required value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom</label>
                                <input type="text" name="lastname" class="form-control" placeholder="Dupont" required value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="jean.dupont@exemple.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="06 12 34 56 78" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse complète</label>
                            <input type="text" name="address" class="form-control" placeholder="12 rue des Gourmets" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Ville</label>
                                <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Code Postal</label>
                                <input type="text" name="zip_code" class="form-control" required value="<?= htmlspecialchars($_POST['zip_code'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="form-text small">
                                <i class="bi bi-info-circle me-1"></i> 
                                10 caractères min. (1 Majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial).
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm fw-bold">
                            S'inscrire 
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <span class="text-muted">Déjà client ?</span> 
                        <a href="index.php?page=login" class="text-decoration-none fw-bold">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . 'includes/footer.php'; ?>