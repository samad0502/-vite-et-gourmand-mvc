<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales - Vite & Gourmand</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>

<body>

    <div class="legal-container">
        <a href="index.php?page=home" class="back-link">← Retour à l'accueil</a>

        <div class="legal-box">
            <h1>Mentions Légales</h1>

            <section>
                <h2>1. Éditeur du site</h2>
                <p><strong>Responsable de publication :</strong> [Ton Prénom et Nom]</p>
                <p><strong>Contact :</strong> <?php echo htmlspecialchars($_ENV['MAIL_USER']); ?></p>
            </section>

            <section>
                <h2>2. Hébergement</h2>
                <p>Le site est hébergé par <strong>Heroku</strong>.</p>
            </section>

            <section>
                <h2>3. Propriété intellectuelle</h2>
                <p>Le contenu est la propriété de Vite & Gourmand.</p>
            </section>

            <section>
                <h2>4. Données personnelles</h2>
                <p>Conformément au RGPD, vous disposez d'un droit d'accès à vos données.</p>
            </section>

            <section>
                <h2>5. Cookies</h2>
                <p>Ce site utilise des cookies techniques strictement nécessaires au fonctionnement du panier et de la session utilisateur. En naviguant sur ce site, vous acceptez leur utilisation.</p>
            </section>
        </div>

</body>

</html>