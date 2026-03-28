<?php require_once ROOT . 'app/Views/includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-cart3"></i> Votre Panier</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            Votre panier est vide. <a href="index.php?page=menus" class="alert-link">Découvrez nos menus !</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Convives</th>
                                    <th>Matériel</th>
                                    <th class="text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?= htmlspecialchars($item['title']) ?></span>
                                            <?php if ($item['isPromo']): ?>
                                                <br><span class="badge bg-success small">Promo -10% appliquée</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= $item['equipment'] ? 'Oui' : 'Non' ?></td>
                                        <td class="text-end fw-bold"><?= number_format($item['subtotal'], 2) ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Résumé</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?= number_format($grandTotal, 2) ?> €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frais de livraison</span>
                            <span class="text-success">Offert</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total</span>
                            <span class="h5 text-primary"><?= number_format($grandTotal, 2) ?> €</span>
                        </div>
                        <button class="btn btn-primary w-100 btn-lg">Procéder au paiement</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once ROOT . 'app/Views/includes/footer.php'; ?>