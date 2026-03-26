<?php

/**
 * Page principale d’affichage des menus

 */
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">

    <h1 class="mb-4"><i class="bi bi-fork-knife"></i>Nos menus</h1>
    <img class="rounded mx-auto d-block pt-2" src="/public/assets/img/imgMenu.jpg" alt="imgMenu" width="100%" height="500px">

    <!-- ZONE DES FILTRES -->

    <!-- Filtres des menus -->
    <form id="filtersForm" class="row mb-4 pt-4">

        <!-- Prix minimum -->
        <div class="col-md-2">
            <input type="number" class="form-control"
                id="priceMin" placeholder="Prix min">
        </div>

        <!-- Prix maximum -->
        <div class="col-md-2">
            <input type="number" class="form-control"
                id="priceMax" placeholder="Prix max">
        </div>

        <!-- Nombre minimum de personnes -->
        <div class="col-md-3">
            <input type="number" class="form-control"
                id="minPeople" placeholder="Personnes minimum">
        </div>

        <!-- Thème -->
        <div class="col-md-2">
    <select id="theme" class="form-control">
        <option value="">Tous les thèmes</option>
        <?php foreach($themes as $t): ?>
            <option value="<?= htmlspecialchars($t) ?>"><?= ucfirst(htmlspecialchars($t)) ?></option>
        <?php endforeach; ?>
    </select>
</div>

        <!-- Régime -->
        <div class="col-md-2">
    <select id="diet" class="form-control">
        <option value="">Tous les régimes</option>
        <?php foreach($diets as $d): ?>
            <option value="<?= htmlspecialchars($d) ?>"><?= ucfirst(htmlspecialchars($d)) ?></option>
        <?php endforeach; ?>
    </select>
</div>

        <!-- Bouton -->
        <div class="col-md-1">
            <button class="btn btn-info w-100">
                Filtrer
            </button>
        </div>

    </form>

    <div class="row" id="menusContainer"></div>

</div>

<!-- JS -->
<script src="assets/js/api.js"></script>
<script src="assets/js/menus.js"></script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/main.js"></script>


</body>

</html>
<?php require_once ROOT . 'includes/footer.php'; ?>