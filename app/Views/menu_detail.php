<?php 
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div id="menuCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <?php foreach ($menu['all_images'] as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="/public/assets/img/menus/<?= trim($img) ?>" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Image menu">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($menu['all_images']) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#menuCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#menuCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 mt-3">
                <span class="badge bg-primary p-2"><?= htmlspecialchars($menu['diet_name'] ?? 'Classique') ?></span>
                <span class="badge <?= $menu['remaining_quantity'] > 0 ? 'bg-success' : 'bg-danger' ?> p-2">
                    Stock : <?= $menu['remaining_quantity'] ?>
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="display-6 fw-bold"><?= htmlspecialchars($menu['title']) ?></h1>
            <p class="h3 text-success mb-4"><?= number_format($menu['price'], 2) ?> € / pers.</p>

            <div class="card border-0 shadow-sm bg-light mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Composition</h6>
                    <p class="small mb-1"><strong>Entrée :</strong> <?= htmlspecialchars($menu['starter']) ?></p>
                    <p class="small mb-1"><strong>Plat :</strong> <?= htmlspecialchars($menu['main_course']) ?></p>
                    <p class="small mb-0"><strong>Dessert :</strong> <?= htmlspecialchars($menu['dessert']) ?></p>
                </div>
            </div>

            <form id="orderForm" class="card p-4 shadow-sm border-0">
                <input type="hidden" id="menu_id" value="<?= $menu['id'] ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Convives (Min: <span id="minPeople"><?= $menu['min_people'] ?></span>)</label>
                    <input type="number" id="number_people" class="form-control" 
                           value="<?= $menu['min_people'] ?>" min="<?= $menu['min_people'] ?>" 
                           data-stock="<?= $menu['remaining_quantity'] ?>">
                    <div id="promo-message" class="mt-2 small p-2 rounded d-none"></div>
                </div>
            <div class="mb-3">
                    <label class="form-label fw-bold">Matériel de service</label>
                    <select id="equipment_ready" class="form-select">
                    <option value="0">Livraison seule</option>
                 <option value="1">Avec prêt de matériel</option>
             </select>
           </div>

                <div id="orderMessage"></div>
                <button type="submit" class="btn btn-success btn-lg w-100" id="orderBtn" 
                        data-logged="<?= $isLogged ? '1' : '0' ?>">
                    Ajouter au panier
                </button>
            </form>
        </div>
    </div>
</div>

<script src="/public/assets/js/menuDetail.js"></script>
<?php require_once ROOT . 'includes/footer.php'; ?>