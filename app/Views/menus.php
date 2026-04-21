<?php
/**
 * Page principale d’affichage des menus
 */
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">

    <h1 class="mb-4"><i class="bi bi-fork-knife"></i> Nos menus</h1>
    <img class="rounded mx-auto d-block pt-2" src="/public/assets/img/imgMenu.jpg" alt="imgMenu" width="100%" height="500px" >
    <!-- ZONE DES FILTRES -->

    <!-- Filtres des menus -->
    <form id="filtersForm" class="row mb-4 pt-4">

    <!-- Prix minimum -->
        <div class="col-md-2">
            <input type="number" class="form-control" id="priceMin" placeholder="Prix min">
        </div>

    <!-- Prix maximum -->
        <div class="col-md-2">
            <input type="number" class="form-control" id="priceMax" placeholder="Prix max">
        </div>

         <!-- Nombre minimum de personnes -->
        <div class="col-md-2">
            <input type="number" class="form-control" id="minPeople" placeholder="Pers. min">
        </div>

        <!-- Thème -->
        <div class="col-md-2">
            <select id="theme" class="form-select">
                <option value="">Tous les thèmes</option>
                <?php foreach ($themes as $themeName): ?>
                    <option value="<?= htmlspecialchars($themeName) ?>">
                        <?= htmlspecialchars($themeName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Régime -->

        <div class="col-md-2">
            <select id="diet" class="form-select">
                <option value="">Tous les régimes</option>
                <?php foreach ($diets as $dietName): ?>
                    <option value="<?= htmlspecialchars($dietName) ?>">
                        <?= htmlspecialchars($dietName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        

        <div class="col-md-2">
            <button type="submit" class="btn btn-info w-100">
                <i class="bi bi-filter"></i> Filtrer
            </button>
        </div>
    </form>

    <div class="row" id="menusContainer">
        <?php if (!empty($menus)): ?>
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/img/<?= htmlspecialchars($menu->getMainImage()) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($menu['title']) ?>"
                             style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($menu->getTitle()) ?></h5>
                            <p class="card-text text-muted small">
                              <?= (strlen($menu['description']) > 80) ? htmlspecialchars(substr($menu['description'], 0, 80)) . '...' : htmlspecialchars($menu['description']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($menu['theme_name']) ?></span>
                                <span class="fw-bold text-primary"><?= number_format($menu['price'], 2) ?> €</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="index.php?page=menu_detail&id=<?= $menu['id'] ?>" class="btn btn-outline-dark btn-sm w-100">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="alert alert-warning text-center">Aucun menu n'est disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="/public/assets/js/menus.js"></script>
<script src="/public/assets/js/filters.js"></script>

<?php require_once ROOT . 'includes/footer.php'; ?>