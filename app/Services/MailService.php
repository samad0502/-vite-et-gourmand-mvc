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
}
