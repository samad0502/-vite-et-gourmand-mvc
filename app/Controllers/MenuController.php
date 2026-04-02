<?php
require_once ROOT . 'app/Models/Menu.php';

class MenuController {
    public function index(){
       $menuModel = new Menu();

       $menus = $menuModel->getAllMenus();
        $themes = $menuModel->getUniqueValues('theme');
        $diets = $menuModel->getUniqueValues('diet');

        require_once ROOT . 'app/Views/menus.php';

        }

    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $menuModel = new Menu();
        $menu = $menuModel->getMenuById($id);
 
        // si le menus n'existe pas  on retourne a la liste
        if(!$menu){
            header('Location: index.php?page=menus');
            exit;
        }
     // on definit si l'utilisateur est connecter pour lz bouton panier
     $isLogges = isset($_SESSION['user']);

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

            $data = [
                'title'       => $_POST['title'],
                'price'       => $_POST['price'],
                'min_people'  => $_POST['min_people'],
                'description' => $_POST['description'],
                'image'       => $imageName
            ];

            $menuModel = new Menu();
            if($menuModel->create($data)){
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

     }