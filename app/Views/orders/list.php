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
                <td class="fw-bold text-primary"><?= htmlspecialchars($order['order_number']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                <td><?= htmlspecialchars($order['menu_title']) ?></td>
                <td><?= $order['number_people'] ?></td>
                <td class="fw-bold"><?= number_format($order['total_price'], 2) ?> €</td>
                <td>
                    <span class="badge bg-<?= $order['order_status'] === 'pending' ? 'warning' : 'success' ?>">
                        <?= $order['order_status'] ?>
                    </span>
                </td>
                <td>
                    <?php if ($order['order_status'] === 'pending'): ?>
                        <a href="index.php?page=edit_order&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i> Modifier
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