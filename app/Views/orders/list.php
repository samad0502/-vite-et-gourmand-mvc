<?php require_once ROOT . 'includes/header.php'; ?>
<?php require_once ROOT . 'includes/navbar.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box-seam me-2"></i>Mes Commandes</h2>
        <a href="index.php?page=menus" class="btn btn-outline-primary btn-sm">Nouvelle commande</a>
    </div>

    <?php if (empty($userOrders)): ?>
        <div class="alert alert-info">Vous n'avez pas encore passé de commande.</div>
    <?php else: ?>
        <div class="table-responsive">
           <table class="table table-hover align-middle shadow-sm">
    <thead class="table-light">
        <tr>
            <th>N° Commande</th>
            <th>Date</th>
            <th>Menu</th>
            <th>Convives</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Actions</th> </tr>
    </thead>
    <tbody>
        <?php foreach ($userOrders as $order): ?>
            <tr>
                <td class="fw-bold text-primary"><?= htmlspecialchars($order->getOrderNumber()) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($order->getOrderDate())) ?></td>
                <td><?= htmlspecialchars($order->getMenuTitle()) ?></td>
                <td><?= $order->getNumberPeople() ?></td>
                <td class="fw-bold"><?= number_format($order->getTotalPrice(), 2) ?> €</td>
                <td>
                    <span class="badge bg-<?= $order->getStatus() === 'pending' ? 'warning' : 'success' ?>">
                        <?= $order->getStatus()  ?>
                    </span>
                </td>
                <td>
                    <?php if ($order->getStatus() === 'finished'): ?>
                       <a href="index.php?page=add_review&id=<?= $order->getId() ?>" class="btn btn-sm btn-info">
                            Laisser un avis
                       </a>
                   <?php endif; ?>

                    <?php if ($order->getStatus()  === 'pending'): ?>
                        <a href="index.php?page=edit_order&id=<?= $order->getId() ?>" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>

                        <a href="index.php?page=cancel_order&id=<?= $order->getId() ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
            <i class="bi bi-trash"></i> Annuler
        </a>
        
                    <?php endif; ?>
                
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
    <?php endif; ?>
</div>

<?php require_once ROOT . 'includes/footer.php'; ?>