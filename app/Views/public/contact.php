<?php
require_once ROOT . 'includes/header.php';
require_once ROOT . 'includes/navbar.php';

if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="container my-5">
    <div class="row g-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-4">Contactez-nous</h2>
            <p class="text-muted">Une question sur votre commande ou sur nos menus ? Laissez-nous un message.</p>

            <!-- Affichage du message de confirmation -->
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="alert alert-success">
                    Votre message a bien été envoyé ! Notre équipe vous répondra dans les plus brefs délais.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="alert alert-danger">
                    Désolé, une erreur est survenue lors de l'envoi. Veuillez réessayer.
                </div>
            <?php endif; ?>


            <form action="index.php?page=process_contact" method="POST" class="p-4 shadow-sm rounded bg-light">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sujet</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Envoyer le message</button>
            </form>
        </div>
    </div>
</div>
       <?php require_once ROOT . 'includes/footer.php'; ?>