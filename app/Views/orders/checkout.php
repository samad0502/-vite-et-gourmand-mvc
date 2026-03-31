<?php


require_once ROOT . 'includes/header.php';
require_once ROOT . 'includes/navbar.php';

?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="bi bi-truck"></i> Détails de la prestation</h4>
                </div>
                <div class="card-body">
                    <form action="index.php?page=process_checkout" method="POST" id="checkoutForm">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Client</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($u['email']) ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Téléphone</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($u['phone']) ?>" readonly>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lieu de la prestation (Adresse complète)</label>
                            <textarea name="address" class="form-control mb-2" required><?= htmlspecialchars($u['address']) ?></textarea>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" id="cityInput" name="city" class="form-control" placeholder="Ville" value="<?= htmlspecialchars($u['city']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="distance" id="distanceInput" class="form-control" placeholder="KM de Bordeaux">
                                    <small class="text-muted">Si hors Bordeaux</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date de prestation</label>
                                <input type="date" name="delivery_date" class="form-control" required min="<?= date('Y-m-d', strtotime('+2 days')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Heure souhaitée</label>
                                <input type="time" name="delivery_time" class="form-control" value="12:00" required>
                            </div>
                        </div>

                        <p>
                            <input type="checkbox" id="cgv" name="cgv" required>
                            <label for="cgv">J'ai lu et j'accepte les <a href="cgv.php" target="_blank">Conditions Générales de Vente</a></label>
                        </p>
                        <input type="hidden" name="final_total_price" id="final_total_price_input">
                        <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                            Valider et payer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">Résumé de la commande</h5>
                </div>
                <div class="card-body">
                    <?php
                    foreach ($_SESSION['cart'] as $item):
                        $stmt = $pdo->prepare("SELECT title, price, min_people FROM menus WHERE id = ?");
                        $stmt->execute([$item['menu_id']]);
                        $m = $stmt->fetch(PDO::FETCH_ASSOC);

                        $nbPers = (int)$item['number_people'];
                        $prixBase = (float)$m['price'] * $nbPers;

                        //  Règle de remise de 10%
                        $remise = 0;
                        if ($nbPers >= ($m['min_people'] + 5)) {
                            $remise = $prixBase * 0.10;
                        }
                        $prixFinalLigne = $prixBase - $remise;
                        $totalMenus += $prixFinalLigne;
                    ?>
                        <div class="mb-3 border-bottom pb-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold"><?= htmlspecialchars($m['title']) ?></span>
                                <span><?= number_format($prixFinalLigne, 2) ?> €</span>
                            </div>
                            <small class="text-muted"><?= $nbPers ?> convives x <?= number_format($m['price'], 2) ?> €</small>
                            <?php if ($remise > 0): ?>
                                <div class="text-success small fw-bold">-10% Remise (Volume atteint)</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-between mt-3">
                        <span>Frais de livraison</span>
                        <span id="deliveryDisplay">0.00 €</span>
                    </div>
                    <small id="deliveryNote" class="text-muted d-block mb-2">Gratuit sur Bordeaux</small>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h4">Total TTC</span>
                        <span class="h3 text-primary fw-bold" id="finalTotal" data-base="<?= $totalMenus ?>">
                            <?= number_format($totalMenus, 2) ?> €
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once ROOT . 'includes/footer.php'; ?>