<?php 
class CookieController {

// definit le consentement et redirige l'utilisateur
    public function setConsent() {
        $choice = $_GET['choice'] ?? 'refused';

// configuration securisée des cookies 
    $cookieOptions = [
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ];
    
    if ($choice === 'accepted') {
        $cookieOptions['expires'] = time() + (3600 * 24 * 30);
        setcookie('cookie_consent', 'accepted', $cookieOptions);
    } else {
        $cookieOptions['expires'] = time() + (3600 * 24);
        setcookie('cookie_consent', 'refused', $cookieOptions);
    }

    // redirection vers la page precedente ou accueil
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
    header("Location: " . $redirect);
    exit();
    }
}