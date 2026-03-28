<?php 
require_once ROOT . 'includes/header.php'; 
require_once ROOT . 'includes/navbar.php'; 
?>

<div class="container my-5">
    <h2><i class="bi bi-cart3"></i> Votre Panier</h2>
    <?php if (empty($cartItems)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Convives</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($item['menu']['title']) ?></strong><br>
                                <small class="text-muted"><?= $item['equipment'] ? 'Avec matériel' : 'Livraison seule' ?></small>
                            </td>
                            <td>
                                <form action="index.php?page=update_cart" method="POST">
                                    <input type="hidden" name="index" value="<?= $item['index'] ?>">
                                    <input type="number" name="quantity" class="form-control" 
                                           value="<?= $item['quantity'] ?>" 
                                           min="<?= $item['menu']['min_people'] ?>" 
                                           style="width: 80px;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>
                                <span class="<?= $item['isPromo'] ? 'text-success fw-bold' : '' ?>">
                                    <?= number_format($item['subtotal'], 2) ?> €
                                </span>
                            </td>
                            <td>
                                <a href="index.php?page=remove_from_cart&index=<?= $item['index'] ?>" class="text-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="col-lg-4">
                <div class="card p-3 bg-light border-0">
                    <h5>Récapitulatif</h5>
                    <div class="d-flex justify-content-between">
                        <span>Sous-total</span>
                        <span><span id="display_subtotal_visible"><?= number_format($totalGeneral, 2) ?></span> €</span>
                        <span id="display_subtotal" class="d-none"><?= $totalGeneral ?></span>
                    </div>
                    
                    <div class="mt-3">
                        <label>Ville de livraison</label>
                        <select id="delivery_city" class="form-select">
                            <option value="inside">Ma Ville (Gratuit)</option>
                            <option value="outside">Hors zone (+ frais)</option>
                        </select>
                    </div>

                    <div id="distance_container" class="mt-2 d-none">
                        <label>Distance (km)</label>
                        <input type="number" id="distance_km" class="form-control" placeholder="10">
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <span>Livraison</span>
                        <span>+ <span id="display_delivery">0.00</span> €</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong class="text-primary"><span id="display_total"><?= number_format($totalGeneral, 2) ?></span> €</strong>
                    </div>
                    <button class="btn btn-success w-100 mt-3">Commander</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="assets/js/panier.js"></script>
<?php require_once ROOT . 'includes/footer.php'; ?>