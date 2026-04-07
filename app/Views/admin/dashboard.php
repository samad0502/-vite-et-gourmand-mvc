<?php 
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container mt-4">
    <h2 class="fw-bold border-bottom pb-2">Espace Administrateur</h2>

    <ul class="nav nav-tabs my-4" id="adminTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees-pane">Gestion Employés</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-pane">Statistiques & CA</button>
        </li>
    </ul>

    <div class="tab-content">
        
        <div class="tab-pane fade show active" id="employees-pane">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Équipe Vite & Gourmand</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Créer un employé</button>
            </div>

            <table class="table table-hover shadow-sm bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $emp['is_active'] ? 'success' : 'danger' ?>">
                                <?= $emp['is_active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <form action="index.php?page=toggle_user" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $emp['id'] ?>">
                                <button type="submit" class="btn btn-sm <?= $emp['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                    <?= $emp['is_active'] ? 'Désactiver' : 'Activer' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="stats-pane">
            <form method="GET" action="index.php" class="row g-3 mb-4 bg-light p-3 rounded">
                <input type="hidden" name="page" value="admin_dashboard">
                <div class="col-md-3">
                    <label class="form-label small">Date début</label>
                    <input type="date" name="start" class="form-control" value="<?= $_GET['start'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Date fin</label>
                    <input type="date" name="end" class="form-control" value="<?= $_GET['end'] ?? '' ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">Filtrer</button>
                </div>
            </form>

            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-success text-white p-4 mb-3 border-0 shadow-sm">
                        <h6>CHIFFRE D'AFFAIRES TOTAL</h6>
                        <h2 class="fw-bold"><?= number_format($totalCA, 2, ',', ' ') ?> €</h2>
                        <p class="small mb-0">Volume : <?= array_sum($menuStats) ?> ventes</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card p-3 shadow-sm border-0">
                        <canvas id="salesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="index.php?page=create_employee" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel Employé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" name="firstname" class="form-control" placeholder="Prénom" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="lastname" class="form-control" placeholder="Nom" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email (Username)" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <p class="small text-muted">L'employé recevra un mail de notification sans le mot de passe.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#employees-pane" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer le compte</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($menuStats)) ?>,
            datasets: [{
                label: 'Nombre de ventes par menu',
                data: <?= json_encode(array_values($menuStats)) ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.8)',
                borderColor: '#0d6efd',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>

<?php require_once ROOT . 'includes/footer.php'; ?>