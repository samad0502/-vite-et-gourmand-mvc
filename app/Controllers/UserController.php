<?php
use App\Services\MailService;
class UserController {

    private function getRepo() {
        $db = (new Database())->getConnection();
        return new UserRepository($db);
    }

    public function updateProfile() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $repo = $this->getRepo();

          $data = [
                'firstname' => htmlspecialchars($_POST['firstname']),
                'lastname'  => htmlspecialchars($_POST['lastname']),
                'phone'     => htmlspecialchars($_POST['phone']),
                'address'   => htmlspecialchars($_POST['address'])
            ];

            if($repo->update($userId, $data)) {
               
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
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
                header('Location: index.php?page=contact&status=error');
                exit;
            
            }
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $subject = htmlspecialchars($_POST['subject']);
            $message = htmlspecialchars($_POST['message']);

            if(empty($name) || empty($email) || empty($message)) {
                header('Location: index.php?page=contact&status=error');
                exit;
            }

          
          try {
            $mailService = new MailService();
            $mailService->sendContactMessage($name, $email, $subject, $message);
            
            header('Location: index.php?page=contact&status=success');
        } catch (\Exception $e) {
            error_log("Erreur Contact Mail : " . $e->getMessage());
            header('Location: index.php?page=contact&status=error');
        }
        exit;

          }
        }
    
}