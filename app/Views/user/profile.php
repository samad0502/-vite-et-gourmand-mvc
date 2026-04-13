<?php

if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

$user = $_SESSION['user'];

require_once ROOT . 'includes/header.php';
require_once ROOT . 'includes/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-center">Modifier mon profil</h4>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success border-0 small">Profil mis à jour avec succès !</div>
                    <?php endif; ?>

                    <form action="index.php?page=update_profile" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Prénom</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                                    <input type="text" name="firstname" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nom</label>
                                <input type="text" name="lastname" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Numéro de téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                <input type="tel" name="phone" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="06XXXXXXXX">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Adresse de livraison par défaut</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control bg-light border-0" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark py-2 fw-bold">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . 'includes/footer.php'; ?>