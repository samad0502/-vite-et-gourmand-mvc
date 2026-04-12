

<footer class="bg-dark text-light mt-5">
    <div class="text-center py-3">
        <h5><i class="bi bi-clock me-2"></i>Nos horaires</h5>
        <div class="d-flex flex-wrap justify-content-center gap-3 small">
            <?php 
$hours_to_display = $opening_hours ?? $GLOBALS['opening_hours'] ?? []; 
?>

<div class="d-flex flex-wrap justify-content-center gap-3 small">
    <?php if (!empty($hours_to_display)): ?>
        <?php foreach ($hours_to_display as $hour): ?>
            <span>
                <strong><?= htmlspecialchars($hour['day_name']) ?> :</strong>
                <?php if ($hour['is_closed']): ?>
                    <span class="text-danger">Fermé</span>
                <?php else: ?>
                    <?= date('H\hi', strtotime($hour['open_time'])) ?> - <?= date('H\hi', strtotime($hour['close_time'])) ?>
                <?php endif; ?>
            </span>
        <?php endforeach; ?>
    <?php else: ?>
        <span>Horaires non disponibles</span>
    <?php endif; ?>
</div>
        </div>
    </div>
<!--
    <?php //if (!isset($_COOKIE['cookie_consent'])): ?>
        <div id="cookie-banner" class="fixed-bottom bg-dark text-white p-4 shadow-lg border-top border-primary" style="z-index: 9999;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">Respect de votre vie priv&eacute;e 🍪</h5>
                        <p class="small mb-0 text-secondary">
                            Nous utilisons des cookies pour optimiser votre exp&eacute;rience de commande.
                            Certains sont essentiels, d'autres nous aident &agrave; am&eacute;liorer nos services.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="../ajax/set_cookie.php?choice=refused" class="btn btn-outline-light btn-sm me-2">Refuser</a>
                        <a href="../ajax/set_cookie.php?choice=accepted" class="btn btn-primary btn-sm">Tout accepter</a>
                    </div>
                </div>
            </div>
        </div>  --> 
    <?php // endif; ?> 


    <div class="container py-4">
        <div class="row">
            <div class="col-lg-6 col-md-8 mx-auto mx-lg-0">
                <div class="card row g-0 flex-column flex-lg-row overflow-hidden border-secondary">
                    <div class="col-lg-4 bg-white d-flex align-items-center justify-content-center p-2">
                        <img class="img-fluid" src="/public/assets/img/imgAcc/logo.ico" alt="logo" style="max-height: 120px;" />
                    </div>
                    <div class="col-lg-8 card-body bg-dark text-light">
                        <p class="mb-2"><strong>Vite & Gourmand</strong><br> 5 avenue de la liberté <br> 33000 Bordeaux</p>
                        <p class="mb-1"><i class="bi bi-telephone"></i> : 0500000000 </p>
                        <p class="mb-0"><i class="bi bi-at"></i> : contact@vite-gourmand.fr</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end py-2 mt-3">
            <a href="/" class="text-light me-2">Accueil</a>
            <a href="/contact.php" class="text-light me-2">Contact</a>
            <a href="/mentions-legales.php" class="text-light me-2">Mentions légales</a>
            <a href="/cgv.php" class="text-light">CGV</a>
        </div>
    </div>

    <div class="text-center py-2" style="background-color: #660909;">
        © 2025 Vite & Gourmand. Tous droits réservés
    </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    AOS.init();
</script>
</body>

</html>