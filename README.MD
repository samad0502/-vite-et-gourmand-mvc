# Vite Gourmand

> Application web de gestion de commandes pour un service de restauration, basée sur une architecture hybride SQL / NoSQL.



## Fonctionnalités Principales

* **Espace Client :** Consultation des menus, configuration du nombre de convives et tunnel de commande sécurisé.
* **Espace Collaborateur :** Gestion du flux de production des commandes (système de workflow linéaire et sécurisé).
* **Tableau de Bord Admin :** Visualisation des statistiques de ventes précises en temps réel avec filtres par date et par menu.
* **Système de Notification :** Envoi automatique d'e-mails de confirmation (MailService).



## Architecture Technique

Ce projet a été conçu en **Architecture MVC** (Modèle-Vue-Contrôleur) avec l'utilisation du pattern **Repository** pour isoler la logique métier de l'accès aux données.

### Base de Données Hybride (Polyglot Persistence)

Pour répondre aux besoins spécifiques de l'application, deux systèmes de stockage distincts sont utilisés :

* **MySQL (Relationnel) :** Gère les données critiques nécessitant une cohérence forte (ACID) : utilisateurs, rôles, menus et le stockage principal des commandes.
* **MongoDB Atlas (NoSQL Documentaire) :** Dédié à la haute performance pour le logging des statistiques de vente et l'historique des annulations de commandes.



##  Technologies Utilisées

* **Back-end :** PHP 8.5 (Orienté Objet, typage strict, sans propriétés dynamiques obsolètes)
* **Bases de données :** MySQL & MongoDB Atlas (via le driver officiel PHP `mongodb/mongodb`)
* **Front-end :** HTML5, CSS3, Bootstrap 5, JavaScript, Chart.js (pour les graphiques dynamiques)
* **Outils & Standards :** Git (Git Flow avec branches `develop` et `main`), Composer (Autoloading)



## Installation et Configuration

### 1. Prérequis
* PHP 8.2 ou supérieur
* Serveur MySQL
* Un cluster MongoDB Atlas (ou une instance locale)

### 2. **Base de données MySQL :**
   * Créer une base nommée `vite_gourmand` dans phpMyAdmin.
   * Importer le fichier situé dans `/sql/vite_gourmand.sql`.
   * Dépendances : Le dossier `vendor/` est inclus. Si nécessaire, exécuter `composer install`.

### 3. Variables d'environnement
Transformer le fichier `.env.example ` en `.env` à la racine du projet sur le modèle suivant :
# Configuration MySQL
DB_HOST=localhost
DB_NAME=vite_gourmand
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

# Configuration Librairie d'envoi de mail
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USER=MAIL_USER
MAIL_PASS=MAIL_PASS
MAIL_FROM=service-client@vite-gourmand.fr
MAIL_FROM_NAME=vite-gourmand

# Configuration MongoDB
MONGODB_URI=mongodb+srv://<user>:<password>@cluster.mongodb.net/vite_gourmand

## Version en ligne

Le projet est déployé à l'adresse suivante : **https://vite-gourmand-app-d37150131136.herokuapp.com**

Projet réalisé par **Abdessamad BECHAA**  dans le cadre du titre professionnel **Développeur Web Et Web Mobile**._