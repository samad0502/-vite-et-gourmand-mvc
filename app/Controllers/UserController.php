<?php

class UserController {
    public function updateProfile() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];

          $data = [
                'firstname' => htmlspecialchars($_POST['firstname']),
                'lastname'  => htmlspecialchars($_POST['lastname']),
                'phone'     => htmlspecialchars($_POST['phone']),
                'address'   => htmlspecialchars($_POST['address'])
            ];
            
            $userModel =new User();

            if($userModel->update($userId, $data)) {
               
            $_SESSION['user']['firstname'] = $data['firstname'];
                $_SESSION['user']['lastname']  = $data['lastname'];
                $_SESSION['user']['phone']     = $data['phone'];
                $_SESSION['user']['address']   = $data['address'];

                header('Location: index/php?page=profile&success=1');
            } else {
                header('Location: index.php?page=profile&error=1');
            }
            exit;
        }
    }


    public function sendContactMessage() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $subject = htmlspecialchars($_POST['subject']);
            $message = htmlspecialchars($_POST['message']);

            if(empty($name) || empty($email) || empty($message)) {
                header('Location: index.php?page=contact&status=error');
                exit;
            }

         //envoi du mail pour la demande de contact
          $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
          
          try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['MAIL_PORT'];
            $mail->CharSet    = 'UTF-8';

            
            $mail->setFrom('contact@vitegourmand.fr', 'Formulaire Contact ViteGourmand');
        
            $mail->addAddress($_ENV['MAIL_USER'], 'Admin Vite & Gourmand');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "Nouveau message : " . $subject;
            $mail->Body    = "
                <h3>Nouveau message de contact</h3>
                <p><strong>Nom :</strong> {$name}</p>
                <p><strong>Email :</strong> {$email}</p>
                <p><strong>Sujet :</strong> {$subject}</p>
                <p><strong>Message :</strong><br>{$message}</p>
            ";

            $mail->send();
            header('Location: index.php?page=contact&status=success');
        } catch (\Exception $e) {
            error_log("Erreur Contact Mail : " . $mail->ErrorInfo);
            header('Location: index.php?page=contact&status=error');
        }
        exit;

          }
        }
    
}