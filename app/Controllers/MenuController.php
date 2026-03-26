<?php
require_once ROOT . 'app/Models/Menu.php';

class MenuController {
    public function index(){
        $database = new Database();
        $db = $database->getConnection();    
        $menuModel = new Menu();
        $menus = $menuModel->getAllMenus();
        $themes = $menuModel->getUniqueValues('theme');
        $diets = $menuModel->getUniqueValues('diet');

        require_once ROOT . 'app/Views/menus.php';
        }
}