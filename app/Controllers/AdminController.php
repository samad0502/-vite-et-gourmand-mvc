<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Repositories\StatRepository;
class AdminController {

    private function getRepo() {
        $db = (new Database())->getConnection();
        return new AdminRepository($db);
    }

    public function dashboard() {
        if($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $adminRepo = $this->getRepo();

        $orders = $adminRepo->getPendingOrders();
        $totalEmployees = $adminRepo->countEmployees();

        $totalOrders = $adminRepo->countTotalOrders();
       
        require_once ROOT . 'app/Views/admin/dashboard.php';
    }


    //gestion des utilisateurs
    public function users() {
        if($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
}
        $adminRepo = $this->getRepo();
        $employees = $adminRepo->getAllEmployees();
        
        require_once ROOT . 'app/Views/admin/users.php';
    }


    //statistiques
    public function stats() {
        if($_SESSION['user']['role'] !== 'admin'){
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $statRepo = new StatRepository();
        
        //recuperation des filtres
        $selectMenu = $_GET['menu_filter'] ?? '';
        $startDate = $_GET['date_start'] ?? '';
        $endDate = $_GET['date_end'] ?? '';
        
        //données pour les stats
        $statsData = $statRepo->getFilteredStats($selectMenu, $startDate, $endDate);

        // traitement des données pour Chart.js
        $menuStats = [];
        $totalCA = 0;
        foreach($statsData as $doc) {
            $name = $doc['menu_name'] ?? 'Non défini';
            //on compte le nb de commande pour le graphique chart.js
            $menuStats[$name] = ($menuStats[$name] ?? 0 ) + 1 ;
            // cumul du prix total calculé
            $totalCA += $doc['price'] ?? 0;
        }

       // On récupère la liste des menus depuis SQL
       $menuRepo = new MenuRepository($db);
       $menusFromSql = $menuRepo->findAll(); 

       $allMenus = [];
       foreach($menusFromSql as $m) {

        $allMenus[] = $m->getTitle(); 
    }
        
        
        require_once ROOT . 'app/Views/admin/stats.php';
       
        }
    

            public function createEmployee() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $adminRepo = $this->getRepo();

                try {
                
                //delegation de l'insertion sql au modele
                $success = $adminRepo->addEmployee($_POST);
              
                //envoi du mail d'inscription employé (sans mdp)
                if($success) {
                $this->sendEmployeeNotification ($_POST['email'], $_POST['firstname']);
                 header('location: index.php?page=admin_users&success=1');
                }
               
            } catch (PDOException $e) {
                header('Location: index.php?page=admin_users&error=already_exists');
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
                $adminRepo = $this->getRepo();
                $adminRepo->toggleUserStatus((int)$_GET['id']);

                header('Location: index.php?page=admin_users&success=status_updated');
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