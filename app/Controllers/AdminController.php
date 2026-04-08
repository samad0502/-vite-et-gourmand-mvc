<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class AdminController {
    public function dashboard() {
        if($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $stmt = $db->query("SELECT o.*, u.firstname, m.title as menu_name
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            JOIN menus m ON o.menu_id = m.id
                            WHERE o.order_status NOT IN ('finished', 'cancelled'");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);    
        
        require_once ROOT . 'app/Views/admin/dashboard.php';
    }


    //gestion des utilisateurs
    public function users() {
        if($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
}
        $db = (new Database())->getConnection();
        $adminModel = new Admin($db);
        $employees = $adminModel->getAllEmployees();
        
        require_once ROOT . 'app/Views/admin/users.php';
    }


    //statistiques
    public function stats() {
        if($_SESSION['user']['role'] !== 'admin'){
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $adminModel = new Admin($db);
        //données pour les stats
        $statsData = $adminModel->getMongoStats($_GET['menu'] ?? '', $_GET['start'] ?? '', $_GET['end'] ?? '');

        // traitement des données pour Chart.js
        $menuStats = [];
        $totalCA = 0;
        foreach($statsData as $doc) {
            $name = $doc['menu_name'];
            $menuStats[$name] = ($menuStats[$name] ?? 0 ) + 1 ;
            $totalCA += $doc['price'];
        }

        require_once ROOT . 'app/Views/admin/stats.php';
       
        }
    

            public function createEmployee() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $db =(new Database())->getConnection();
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
                try {
                //insertion role_id(employé ou 2)
                $stmt = $db->prepare("INSERT INTO users (firstname, lastname, email, password, role_id, is_active) VALUES(?, ?, ?, ?, 2, 1)");
                $stmt->execute([$_POST['firstname'], $_POST['lastname'], $email, $password]);

                //envoi du mail d'inscription employé (sans mdp)
                $this->sendEmployeeNotification($email, $_POST['firstname']);

                header('location: index.php?page=admin_dashboard&success=created');
            } catch (PDOException $e) {
                header('Location: index.php?page=admin_dashboard&error=exists');
            }
            exit;
        }
        }

               // active ou desactive un compte utilisateur
        public function toggleUser() {
            if ($_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
                $db = (new Database())->getConnection();
                $userId = (int)$_POST['user_id'];

                $stmt = $db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$userId]);

                header('Location: index.php?page=admin_dashboard&success=status_updated');
                exit;
            }
        }

        //envoi mail de bienvenue a un nouveau employé(sans mdp)
       private function sendEmployeeNotification($email, $firstname) {
        $mail = new PHPMailer(true);
        try {
             $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->CharSet    = 'UTF-8';

            $mail->setFrom('admin@vitegourmand.fr', 'Direction Vite & Gourmand');
            $mail->addAddress($email, $firstname);

            $mail->isHTML(true);
            $mail->Subject = "Bienvenue dans l'équipe, $firstname !";
            
            $mail->Body = "
                <h2>Félicitations $firstname !</h2>
                <p>Ton compte employé a été créé avec succès sur la plateforme <strong>Vite & Gourmand</strong>.</p>
                <p>Tu peux désormais te connecter avec ton adresse email : <strong>$email</strong>.</p>
                <p style='color: red;'><strong>Note importante :</strong> Pour des raisons de sécurité, ton mot de passe ne figure pas dans ce mail. Merci de te rapprocher de l'administrateur pour l'obtenir.</p>
                <br>
                <p>À très vite en cuisine !</p>";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur mail employé : " . $mail->ErrorInfo);
        } 
}
}