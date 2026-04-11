
<?php

require_once ROOT . 'includes/header.php';
require_once ROOT .  'includes/navbar.php';
?>


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold mb-0">Gestion des Employés</h2>
            <p class="text-muted small mb-0">Interface d'administration des comptes</p>
        </div>
        <a href="index.php?page=admin_dashboard" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left-circle me-2"></i>Retour au Dashboard
        </a>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Nouvel Employé
                </div>
                <div class="card-body">
                    <form action="index.php?page=create_employee" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Prénom</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nom</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email (Identifiant)</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mot de passe provisoire</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="form-text text-danger" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle"></i> Ne sera pas envoyé par mail (sécurité José).
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Créer le compte</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h3 class="mb-4">Liste des Employés</h3>
            <div class="table-responsive bg-white shadow-sm rounded">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
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
                                <td><strong><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></strong></td>
                                <td><?= htmlspecialchars($emp['email']) ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $emp['is_active'] ? 'success' : 'danger' ?>">
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
        </div>
    </div>
</div>

 <?php require_once ROOT .  'includes/footer.php'; ?>