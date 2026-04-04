
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">

    <div class="container">
        <a class="navbar-brand" href="index.php?page=home">
            <img src="/public/assets/img/imgAcc/logo.ico" alt="logo" width="100">
        </a>

        <button class=" navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home"><i class="bi bi-house-fill text-light"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=menus"><i class="bi bi-fork-knife text-light"></i> Menus</a>
                </li>
                <?php if (isset($_SESSION['user'])) : ?>

                    <?php if (isset($_SESSION['user']))

                        $userRole = strtolower($_SESSION['user']['role_name'] ?? $_SESSION['user']['role'] ?? '');
                    ?>

                    <?php if ($userRole === 'utilisateur') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=orders">
                                <i class="bi bi-box-seam-fill text-light"></i> Mes commandes
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($userRole === 'employee') : ?>
                        <li class="nav-item">
                            <a class="nav-link text-info fw-bold" href="index.php?page=employee_dashboard">
                                <i class="bi bi-clipboard-check-fill"></i> Suivi Commandes
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($userRole === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="/admin/admin_dashboard.php">
                                <i class="bi bi-shield-lock-fill"></i> Accueil Admin
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>


                <li class="nav-item">
                    <a class="nav-link" href="/contact.php"><i class="bi bi-envelope-fill text-light"></i> Contact</a>
                </li>

                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item text-light mt-2 ms-lg-3">
                        Bienvenue <?= htmlspecialchars($_SESSION['user']['firstname']) ?>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout"><i class="bi bi-power text-light"></i> Déconnexion</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " href="/profile.php">
                            <i class="bi bi-person-gear me-2 text-light"></i>Mon Profil
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=login"><i class="bi bi-person-fill text-light"></i> Connexion</a>
                    </li>
                <?php endif; ?>

                <?php
                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                ?>

                <li class="nav-item">
                    <a class="nav-link position-relative" href="index.php?page=cart">
                        <i class="bi bi-cart3 text-light"></i> Panier
                        <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="col row align-item-end "><img src="/public/assets/img/imgAcc/barreVG.jpg" alt="barreVG"></div>