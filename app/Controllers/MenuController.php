<?php
require_once ROOT . 'app/Models/Menu.php';

class MenuController {
    public function index(){
        $database = new Database();
        $db = $database->getConnection();    
        $menuModel = new Menu($db);
        $menus = $menuModel->getAll();

        require_once ROOT . 'app/Views/menus.php';
        }
}