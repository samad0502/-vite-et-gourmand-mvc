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


     }