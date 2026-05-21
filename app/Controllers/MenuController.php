<?php
require_once ROOT . 'app/Repositories/MenuRepository.php';

class MenuController {

private function getRepo() {
        $db = (new Database())->getConnection();
        return new MenuRepository($db);
    }

    public function index(){
       $menuRepo = $this->getRepo();

       $menus = $menuRepo->findAll();
        $themes = $menuRepo->getUniqueAttributes('theme');
        $diets = $menuRepo->getUniqueAttributes('diet');

        require_once ROOT . 'app/Views/menus.php';

        }

        public function apiMenus() {
    $repo = $this->getRepo();
    
    if (!empty($_GET['theme']) || !empty($_GET['diet']) || !empty($_GET['priceMax'])) {
        $menus = $repo->findWithFilters($_GET);
    } else {
        $menus = $repo->findAll();
    }  

    header('Content-Type: application/json');
    echo json_encode($menus);
    exit;
}

    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $menuRepo = $this->getRepo();
        
        $menu = $menuRepo->findById($id);
 
        // si le menus n'existe pas  on retourne a la liste
        if(!$menu){
            header('Location: index.php?page=menus');
            exit;
        }

     // on definit si l'utilisateur est connecté pour les bouton panier
     $isLogged = isset($_SESSION['user']);

     require_once ROOT . 'app/Views/menu_detail.php';

     }

     //affiche le formulaire d'ajout
     public function add() {
        $this->checkAccess();
        require_once ROOT . 'app/Views/employee/add_menu.php';
     }


//la soumission du formulaire
     public function store() {
        $this->checkAccess();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imageName = $this->handleUpload();
            $menuRepo = $this->getRepo();

            $data = [
                'title'              => $_POST['title'],
                'price'              => $_POST['price'],
                'min_people'         => $_POST['min_people'],
                'description'        => $_POST['description'],
                'image'              => $imageName,
                'remaining_quantity' => $_POST['remaining_quantity'],
                'theme_id'           => $_POST['theme_id'],
                'diet_id'            => $_POST['diet_id']
            ];

            
            if($menuRepo->create($data)){
                header('Location: index.php?page=employee_dashboard&success=menu_added#menus-pane');
            } else {
                header('Location: index.php?page=add_menu&error=save_failed');
            }
            exit;
        }
     }


     private function handleUpload() {
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $name = time() . '_' . $_FILES['image']['name'];
            $target = ROOT . 'public/assets/img/menus/' . $name;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                return $name;
            }
        }
        return null;
     }


    private function checkAccess(){
        if(!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'employee' && $_SESSION['user']['role'] !== 'admin')){
            header('Location: index.php?page=login');
            exit;
        }
    }


    public function edit($id) {
        $this->checkAccess();
        $menuRepo = $this->getRepo();
        
        $menu = $menuRepo->findById($id);

        if(!$menu) {
            header('Location: index.php?page=employee_dashboard&error=not_found');
            exit;
        }

        require_once ROOT . 'app/Views/employee/edit_menu.php';
    }


    public function update() {
        $this->checkAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['menu_id'];
            $menuRepo = $this->getRepo();
            $oldMenu = $menuRepo->findById($id);

            // gestion de l'image , si elle est chargée on l'utilise sinon on garde l'ancienne
            $imageName = $oldMenu->getImage();
            if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imageName = $this->handleUpload();
            }

            $data = [
            'title'       => $_POST['title'],
            'price'       => $_POST['price'],
            'min_people'  => $_POST['min_people'] ?? $oldMenu->getMinPeople(),
            'description' => $_POST['description'],
            'image'       => $imageName  
            ];

            if($menuRepo->update($id, $data)) {
                header('Location: index.php?page=employee_dashboard&#menus-pane');
            } else {
                header("Location: index.php?page=edit_menu&id=$id&error=update_failed");
            }
            exit;
        }
    }

    public function deleteAddedMenu() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: index.php?page=employee_dashboard');
            exit();
        }

        // 1. On récupère l'ID (on enlève les echo/die de débogage pour que ça fonctionne)
        $menuId = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : -1;

        // 2. MODIFICATION : On accepte l'ID 0 (supérieur ou égal à 0)
        if($menuId >= 0){
            $db = (new Database())->getConnection();
            $menuRepo = new MenuRepository($db);

            if($menuRepo->deleteById($menuId)){
                $_SESSION['success_message'] = "Le menu a bien été supprimé.";
            } else {
                $_SESSION['error_message'] = "Impossible de supprimer le menu.";
            }
        }
        
        header('Location: index.php?page=employee_dashboard');
        exit();
    }

     }