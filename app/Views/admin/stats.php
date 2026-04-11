<?php

require_once ROOT . 'includes/header.php';
require_once ROOT .  'includes/navbar.php';
?>

<div class="container my-5">

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-bar-chart-line text-success me-2"></i>Analyses Précises
                </h2>
                <p class="text-muted mb-0">Données issues de MongoDB Atlas</p>
            </div>
            <a href="index.php?page=admin_dashboard" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>


        <button onclick="window.print();" class="btn btn-dark ms-2">
            <i class="bi bi-printer me-2"></i>Imprimer le rapport
        </button>

        <div class="card p-4 shadow-sm mb-4 border-0 bg-light">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="admin_stats">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Filtrer par Menu</label>
                    <select name="menu_filter" class="form-select">
                        <option value="">Tous les menus</option>
                        <?php foreach ($allMenus as $m): ?>
                            <option value="<?= $m ?>" <?= ($_GET['menu_filter'] ?? '') == $m ? 'selected' : '' ?>>
                                <?= $m ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Depuis le</label>
                    <input type="date" name="date_start" class="form-control" value="<?= $_GET['date_start'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jusqu'au</label>
                    <input type="date" name="date_end" class="form-control" value="<?= $_GET['date_end'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark text-white p-4 h-100">
                    <h6 class="opacity-75">CHIFFRE D'AFFAIRES (FILTRÉ)</h6>
                    <h2 class="text-success"><?= number_format($totalCA, 2, ',', ' ') ?> €</h2>
                    <hr>
                    <p class="small mb-0">Basé sur <?= array_sum($menuStats) ?> article(s) vendu(s).</p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-4 shadow-sm">
                    <canvas id="filterChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('filterChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($menuStats)) ?>,
                datasets: [{
                    label: 'Volume de ventes',
                    data: <?= json_encode(array_values($menuStats)) ?>,
                    backgroundColor: 'rgba(25, 135, 84, 0.6)'
                }]
            }
        });
    </script>

    <?php require_once ROOT .  'includes/footer.php'; ?>