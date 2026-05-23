<?php 
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div id="menuCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <?php foreach ($menu->getAllImages()  as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="/public/assets/img/menus/<?= trim($img) ?>" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Image menu">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($menu->getAllImages() ) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#menuCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#menuCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 mt-3">
                <span class="badge bg-primary p-2"><?= htmlspecialchars($menu->getDietName() ?? 'Classique') ?></span>
                <span class="badge <?= $menu->getRemainingQuantity() > 0 ? 'bg-success' : 'bg-danger' ?> p-2">
                    Stock : <?= $menu->getRemainingQuantity() ?>
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="display-6 fw-bold"><?= htmlspecialchars($menu->getTitle()) ?></h1>
            <p class="h3 text-success mb-4"><?= number_format($menu->getPrice(), 2) ?> € / pers.</p>

            <div class="card border-0 shadow-sm bg-light mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Composition</h6>
                    <p class="small mb-1"><strong>Entrée :</strong> <?= htmlspecialchars($menu->getStarter() ?? '') ?></p>
                    <p class="small mb-1"><strong>Plat :</strong> <?= htmlspecialchars($menu->getMainCourse() ?? '') ?></p>
                    <p class="small mb-0"><strong>Dessert :</strong> <?= htmlspecialchars($menu->getDessert() ?? '')  ?></p>
                </div>
            </div>

            <form action="index.php?page=add_to_cart" method="POST" id="orderForm" class="card p-4 shadow-sm border-0">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="menu_id" id="menu_id" value="<?= $menu->getId() ?>">
    
    <div class="mb-3">
        <label class="form-label fw-bold">Convives(Min: <span id="minPeople"><?= $menu->getMinPeople() ?></span>)</label>
        <input type="number" name="number_people" id="number_people" class="form-control" 
               value="<?= $menu->getMinPeople() ?>" min="<?= $menu->getMinPeople() ?>" 
               data-stock="<?= $menu->getRemainingQuantity() ?>">
               <div id="promo-message" class="mt-2 small p-2 rounded d-none"></div>
                </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Matériel de service</label>
        <select name="equipment_ready" id="equipment_ready" class="form-select">
            <option value="0">Livraison seule</option>
            <option value="1">Avec prêt de matériel</option>
        </select>
    </div>

    <div id="orderMessage"></div>
    <button type="submit" class="btn btn-success btn-lg w-100" id="orderBtn" 
            data-logged="<?= isset($_SESSION['user']) ? '1' : '0' ?>">
        Ajouter au panier
    </button>
</form>
        </div>
    </div>
</div>




<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Connectez-vous pour commander</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="loginError" class="alert alert-danger d-none"></div>

                <form id="loginForm">
    <input type="hidden" id="loginCsrf" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label fw-bold">Email</label>
        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="exemple@mail.com" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Mot de passe</label>
        <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn btn-success w-100 py-2">Se connecter</button>
</form>
                    

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Pas encore de compte ?</p>
                    <a href="index.php?page=register" class="text-success fw-bold">Créer un compte ici</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/js/menuDetail.js"></script>
<?php require_once ROOT . 'includes/footer.php'; ?>