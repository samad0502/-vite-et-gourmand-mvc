<?php

require_once ROOT . 'includes/header.php';
require_once ROOT . 'includes/navbar.php';

?>

<div class="container-fluid my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h2><i class="bi bi-briefcase me-2 text-primary"></i>Espace Employé</h2>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary p-2">Connecté : <?= htmlspecialchars($_SESSION['user']['firstname']) ?></span>
            <a href="index.php?page=logout" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4 px-3" id="employeeTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders-pane">
                <i class="bi bi-cart-check"></i> Commandes
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#menus-pane">
                <i class="bi bi-menu-button-wide"></i> Carte & Plats
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hours-pane">
                <i class="bi bi-clock"></i> Horaires
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews-pane">
                <i class="bi bi-star"></i> Avis
                <?php if(count($reviews) > 0): ?>
                    <span class="badge bg-danger rounded-pill"><?= count($reviews) ?></span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="employeeTabsContent">
        
        <div class="tab-pane fade show active" id="orders-pane">
            <div class="card shadow-sm border-0 mx-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Gestion des Commandes</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3 mb-4">
                        <input type="hidden" name="page" value="employee_dashboard">
                        <div class="col-md-4">
                            <input type="text" name="client" class="form-control" placeholder="Rechercher par client ou N°..." value="<?= htmlspecialchars($searchClient ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="pending" <?= ($statusFilter ?? '') == 'pending' ? 'selected' : '' ?>>En attente (Pending)</option>
                                <option value="accepted" <?= ($statusFilter ?? '') == 'accepted' ? 'selected' : '' ?>>Acceptée</option>
                                <option value="finished" <?= ($statusFilter ?? '') == 'finished' ? 'selected' : '' ?>>Terminée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client / Téléphone</th>
                                    <th>Menu Commandé</th>
                                    <th>Statut Actuel</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($orders)): ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">Aucune commande trouvée.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($orders as $o): ?>
                                    <tr>
                                        <td>
                                            <strong>#<?= $o->getOrderNumber() ?></strong>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($o->getClientFirstname() ?? '' . ' ' . strtoupper($o->getClientLastname() ?? '')) ?><br>
                                            <small class="text-muted"><i class="bi bi-telephone"></i> <?= $o->getPhone() ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($o->getMenuTitle() ?? '') ?></td>
                                        <td>
                                            <span class="badge bg-<?= getStatusColor($o->getStatus()) ?>">
                                                <?= strtoupper($o->getStatus()) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="index.php?page=update_order_status" method="POST" class="d-flex gap-1">
                                                    <input type="hidden" name="order_id" value="<?= $o->getId() ?>">
                                                    <select name="new_status" class="form-select form-select-sm" style="width: auto;">
                                                        <option value="accepted">Accepter</option>
                                                        <option value="preparing">En cuisine</option>
                                                        <option value="shipping">En livraison</option>
                                                        <option value="delivered">Livrée</option>
                                                        <option value="finished">Terminée</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-success">OK</button>
                                                </form>
                                                <button class="btn btn-sm btn-outline-danger" onclick="openCancelModal(<?= $o->getId() ?>, '<?= $o->getOrderNumber() ?>')">Annuler</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="menus-pane">
            <div class="card shadow-sm border-0 mx-3">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion de la Carte</h5>
                    <a href="index.php?page=add_menu" class="btn btn-sm btn-light"><i class="bi bi-plus-circle"></i> Ajouter un Menu</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Aperçu</th>
                                <th>Titre du Menu</th>
                                <th>Prix Unit.</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $m): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($m->getMainImage())): ?>
                                            <img src="public/assets/img/menus/<?= htmlspecialchars($m->getMainImage()) ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" class="rounded shadow-sm">
                                        <?php else: ?>
                                            <div class="bg-light rounded text-center" style="width: 50px; height: 50px; line-height: 50px;"><i class="bi bi-image"></i></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= htmlspecialchars($m->getTitle()) ?></strong></td>
                                    <td><?= number_format($m->getPrice(), 2) ?> €</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="index.php?page=edit_menu&id=<?= $m->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                            <form action="index.php?page=delete_menu" method="POST" onsubmit="return confirm('Supprimer ce menu ?');">
                                                <input type="hidden" name="menu_id" value="<?= $m->getId() ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="hours-pane">
            <div class="card shadow-sm border-0 mx-3" style="max-width: 800px;">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Horaires d'ouverture</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?page=update_hours" method="POST">
                        <table class="table align-middle">
                            <?php foreach ($opening_hours as $h): ?>
                                <tr>
                                    <td width="150"><strong><?= $h->getDayName() ?></strong></td>
                                    <td><input type="time" name="open[<?= $h->getId() ?>]" value="<?= $h->getOpenTime()?>" class="form-control"></td>
                                    <td><input type="time" name="close[<?= $h->getId() ?>]" value="<?= $h->getCloseTime()?>" class="form-control"></td>
                                    <td class="text-center">
                                        <label class="small d-block text-muted">Fermé</label>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="closed[<?= $h->getId() ?>]" value="1" <?= $h->isClosed() ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <button type="submit" class="btn btn-primary px-4 mt-3">Enregistrer les horaires</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="reviews-pane">
            <div class="card shadow-sm border-0 mx-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Modération des Avis Clients</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($reviews)): ?>
                        <div class="text-center py-4 text-muted">Aucun nouvel avis à modérer.</div>
                    <?php else: ?>
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $rev): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($rev->getAuthorName() ) ?></strong></td>
                                    <td><span class="text-warning">★</span> <?= $rev->getRating()  ?>/5</td>
                                    <td style="max-width: 400px;"><?= htmlspecialchars($rev->getComment()) ?></td>
                                    <td>
                                        <form action="index.php?page=moderate_review" method="POST" class="d-inline">
                                            <input type="hidden" name="review_id" value="<?= $rev->getId() ?>">
                                            <button name="action" value="validate" class="btn btn-success btn-sm">Valider</button>
                                            <button name="action" value="refuse" class="btn btn-danger btn-sm">Refuser</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="index.php?page=cancel_order_employee" method="POST" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Annuler la Commande #<span id="cancelOrderNum"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="order_id" id="cancelOrderId">
                <div class="mb-3">
                    <label class="form-label fw-bold">Comment le client a-t-il été prévenu ?</label>
                    <select name="contact_method" class="form-select" required>
                        <option value="GSM">Appel Téléphonique</option>
                        <option value="Email">Email</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Motif de l'annulation (Interne)</label>
                    <textarea name="reason" class="form-control" rows="3" required placeholder="Ex: Matériel non disponible, client injoignable..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
            </div>
        </form>
    </div>
</div>

<script src="/public/assets/js/employee.js"></script>

<?php require_once ROOT . 'includes/footer.php'; ?>