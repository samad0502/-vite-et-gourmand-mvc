<?php


return [
    'host'       => $_ENV['MAIL_HOST'],
    'auth'       => true,
    'username'   => $_ENV['MAIL_USER'],
    'password'   => $_ENV['MAIL_PASS'],
    'secure'     => 'tls',
    'port'       => $_ENV['MAIL_PORT'],
    'from_email' => 'service-client@vitegourmand.fr',
    'from_name'  => 'Vite & Gourmand',
    'charset'    => 'UTF-8'
];