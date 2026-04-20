<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGV - Vite & Gourmand</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>

<body>
    <div class="legal-container">
        <a href="index.php?page=home" class="back-link">← Retour à l'accueil</a>

        <div class="legal-box">
            <h1>Conditions Générales de Vente</h1>
            <p><em>En vigueur au <?php echo date('d/m/Y'); ?></em></p>

            <section>
                <h2>Article 1 : Objet</h2>
                <p>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre le site <strong>Vite & Gourmand</strong> (ci-après "le Vendeur") et toute personne effectuant un achat (ci-après "le Client") via le site internet.</p>
            </section>

            <section>
                <h2>Article 2 : Produits</h2>
                <p>Les produits proposés sont ceux qui figurent dans le menu du site au moment de la consultation. Les photographies sont les plus fidèles possibles mais n'engagent pas le Vendeur (mention "Suggestion de présentation").</p>
            </section>

            <section>
                <h2>Article 3 : Commande</h2>
                <p>La confirmation de la commande entraîne acceptation des présentes CGV, la reconnaissance d'en avoir parfaite connaissance et la renonciation à se prévaloir de ses propres conditions d'achat. Un mail de confirmation récapitulant la commande sera envoyé via notre système de notification.</p>
                <p>Toute personne non authentifié ne pourra éffectuer de commande que après avoir crée un compte </p>
                <p>L’annulation de commande est possible, tant que la commande est au statut "en attente", la modification est également possible tout est modifiable, sauf le choix du menu.Attention une fois la commande passée au statut "accepté", aucune annulation ni remboursements seront possibles.</p>
            </section>

            <section>
                <h2>Article 4 : Prix et Paiement</h2>
                <p>Les prix sont indiqués en Euros (€) TTC. Le paiement est exigible immédiatement au moment de la commande .</p>
            </section>

            <section>
                <h2>Article 5 : Rétractation (Produits périssables)</h2>
                <p>Conformément à l’article <strong>L221-28 du Code de la consommation</strong>, le droit de rétractation ne peut être exercé pour les contrats de fourniture de biens susceptibles de se détériorer ou de se périmer rapidement. <strong>Par conséquent, toute commande de produits alimentaires sur le site est définitive.</strong></p>
            </section>

            <section>
                <h2>Article 6 : Protection des données</h2>
                <p>Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Ces données sont utilisées exclusivement pour la gestion de vos commandes.</p>
            </section>

            <section>
                <h2>Article 7 : Livraison</h2>
                <p>La livraison est disponible et gratuite pour toute les prestations se situant dans la ville de Bordeaux , cependant toute livraison hors de Bordeaux se verra facturée de 5€ par commande et de 0,59€ par kilommètre</p>
            </section>

            <section>
                <h2>Article 8 : Contact</h2>
                <p>Pour toute question ou réclamation, vous pouvez nous contacter via le formulaire de contact du site ou à l'adresse email : <strong><?php echo htmlspecialchars(getenv('MAIL_FROM') ?: 'contact@vite-gourmand.fr'); ?></strong></p>
            </section>
        </div>

</body>

</html>