<?php
use App\Services\MailService;
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
                $mailService = new MailService();    
                $mailService->sendEmployeeNotification ($_POST['email'], $_POST['firstname']);
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

}