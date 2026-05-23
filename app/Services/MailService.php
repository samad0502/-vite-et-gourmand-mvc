<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;


class MailService {
    private $mailer;
    private $config;

    public function __construct() {
        // On charge le fichier de config
        $this->config = require ROOT . 'Config/mail.php';
        
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    private function setup() {
        $this->mailer->isSMTP();
        $this->mailer->Host       = $this->config['host'];
        $this->mailer->SMTPAuth   = $this->config['auth'];
        $this->mailer->Username   = $this->config['username'];
        $this->mailer->Password   = $this->config['password'];
        $this->mailer->SMTPSecure = $this->config['secure'] === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : $this->config['secure'];
        $this->mailer->Port       = $this->config['port'];
        $this->mailer->CharSet    = $this->config['charset'];
        $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
    }


    // Mail de bienvenue
    public function sendWelcomeEmail($email, $firstname) {
        $this->mailer->clearAddresses(); 
        $this->mailer->addAddress($email, $firstname);

        $this->mailer->isHTML(true);
        $this->mailer->Subject = "Bienvenue chez Vite & Gourmand !";
        $this->mailer->Body    = "<h1>Bonjour " . htmlspecialchars($firstname) . " !</h1><p>Compte créé avec succès.</p>";

        return $this->mailer->send();
    }

    // Formulaire de contact admin
    public function sendContactMessage($name, $email, $subject, $message) {
        $this->mailer->clearAddresses();
        $this->mailer->clearReplyTos(); 
        
        
        $this->mailer->addAddress('admin@vitegourmand.fr', 'Admin Vite & Gourmand');
        $this->mailer->addReplyTo($email, $name);

        $this->mailer->isHTML(true);
        $this->mailer->Subject = "Nouveau message : " . $subject;
        $this->mailer->Body    = "
            <h3>Nouveau message de contact</h3>
            <p><strong>Nom :</strong> {$name}</p>
            <p><strong>Email :</strong> {$email}</p>
            <p><strong>Sujet :</strong> {$subject}</p>
            <p><strong>Message :</strong><br>" . nl2br($message) . "</p>
        ";

        return $this->mailer->send();
    }


    public function sendConfirmationEmail($userEmail, $orderRef, $total) {

       $this->mailer->addAddress($userEmail);

        // Contenu du mail
       $this->mailer->isHTML(true);
       $this->mailer->Subject = "Confirmation de votre commande $orderRef";
       $this->mailer->Body    = "
            <h1>Merci pour votre commande !</h1>
            <p>Nous avons bien reçu votre demande de prestation.</p>
            <ul>
                <li><strong>Référence :</strong> $orderRef</li>
                <p>Montant total : <strong>" . number_format((float)$total, 2, ',', ' ') . " €</strong></p>
            </ul>
            <p>Vous pouvez suivre l'avancement dans votre espace 'Mes Commandes'.</p>";

       return $this->mailer->send();
}


// Notification de création de compte employé
    public function sendEmployeeNotification($email, $firstname) {
        $this->mailer->clearAddresses(); 
        $this->mailer->addAddress($email, $firstname);

        $this->mailer->isHTML(true);
        $this->mailer->Subject = "Bienvenue dans l'équipe, $firstname !";
        
        $this->mailer->Body = "
            <h2>Félicitations $firstname !</h2>
            <p>Ton compte employé a été créé avec succès sur la plateforme <strong>Vite & Gourmand</strong>.</p>
            <p>Tu peux désormais te connecter avec ton adresse email : <strong>$email</strong>.</p>
            <p style='color: red;'><strong>Note importante :</strong> Pour des raisons de sécurité, ton mot de passe ne figure pas dans ce mail. Merci de te rapprocher de l'administrateur pour l'obtenir.</p>
            <br>
            <p>À très vite en cuisine !</p>";

        return $this->mailer->send();
    }



    public function notifyOrderFinished($order) {
            
       $this->mailer->addAddress($order->getEmail(), $order->getClientFirstname());

        // Contenu du mail
       $this->mailer->isHTML(true);
       $this->mailer->Subject = "Votre commande " . $order->getOrderNumber() . " est prete !";

        $reviewLink = "http://localhost:3000/index.php?page=add_review&order_id=" . $order->getId();
       
       $this->mailer->Body    = "
                <h1>Bonne nouvelle " . $order->getClientFirstname() . " !</h1>
                <p>Votre commande est désormais terminée. Nous espérons que vous avez apprécié votre expérience.</p>
                <p>Votre avis est précieux pour nous. Pourriez-vous nous laisser une note ?</p>
                <a href='{$reviewLink}' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                    Donner mon avis
                </a>
                <p>À bientôt chez <strong>Vite & Gourmand</strong> !</p>";

           return $this->mailer->send();
    
        }

   


        // Fonction dédiée à l'envoi du mail d'annulation
public function sendCancellationEmail($order, $reason) {

       $this->mailer->addAddress($order->getEmail(), $order->getClientFirstname());

       $this->mailer->isHTML(true);
       $this->mailer->Subject = "Annulation de votre commande " . $order->getOrderNumber();

       $this->mailer->Body = "
            <h2>Bonjour " . $order->getClientFirstname(). "</h2>
            <p>Nous vous informons que votre commande <strong>#" . $order->getOrderNumber() . "</strong> a dû être annulée.</p>
            <p><strong>Motif de l'annulation :</strong><br><em>{$reason}</em></p>
            <p>Si vous avez des questions, n'hésitez pas à nous recontacter.</p>
            <p>Cordialement,<br>L'équipe Vite & Gourmand</p>
        ";

       return $this->mailer->send();
   
    }

    // Fonction dédiée à la reception du mail d'oubli MDP
    public function sendResetEmail($userEmail, $firstname, $resetLink) {
    $this->mailer->clearAddresses();
    $this->mailer->addAddress($userEmail, $firstname);

    $this->mailer->isHTML(true);
    $this->mailer->Subject = "Réinitialisation de votre mot de passe - Vite & Gourmand";
    $this->mailer->Body    = "
        <h1>Bonjour " . htmlspecialchars($firstname) . " !</h1>
        <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre compte Vite & Gourmand.</p>
        <p>Cliquez sur le lien ci-dessous pour choisir un nouveau mot de passe (ce lien est valable 30 minutes) :</p>
        <p><a href='" . $resetLink . "' style='color: #198754; font-weight: bold;'>Réinitialiser mon mot de passe</a></p>
        <p>Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet e-mail.</p>
    ";

    return $this->mailer->send();
}
}
