<?php 
require_once ROOT . 'includes/header.php';
require_once ROOT . 'includes/navbar.php';
 ?>

<div class="container my-5 text-center">
    <div class="card shadow border-0 p-5 rounded-4">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        </div>
        <h2 class="fw-bold mb-3">Commande validée !</h2>
        <p class="lead text-muted">Merci pour votre confiance. Votre festin est entre de bonnes mains.</p>

        <div class="bg-light p-3 rounded-3 my-4">
            <span class="text-uppercase small fw-bold text-muted d-block mb-1">Numéro de commande</span>
            <span class="h4 fw-bold text-primary"><?= htmlspecialchars($_GET['order_ref'] ?? 'N/A') ?></span>
        </div>

        <div class="d-grid d-sm-flex justify-content-center gap-3">
            <a href="index.php?page=home" class="btn btn-outline-secondary px-4">Accueil</a>
            <a href="index.php?page=orders" class="btn btn-success px-4 shadow-sm">Mes commandes</a>
        </div>
    </div>
</div>

<?php require_once ROOT . 'includes/footer.php'; ?>