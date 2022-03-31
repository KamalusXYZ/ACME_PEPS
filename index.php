<?php

declare(strict_types=1);

use peps\core\Autoload;
use peps\core\Router;

// Initialiser l'autoload (à faire EN PREMIER).
require 'peps/core/Autoload.php';
try {
    Autoload::init();
} catch (Exception $e) {
    exit($e->getMessage());
}

// Initialiser la configuration en fonction de l'IP du serveur (à faire en DEUXIEME).
$serverIP = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_VALIDATE_IP) ?: filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP);
if (!$serverIP)
    exit("Server variable SERVER_ADDR unavailable.");
// ICI VOS CLASSES DE CONFIGURATION EN FONCTION DES ADRESSES IP DE VOS SERVEURS.
(match ($serverIP) {
    '127.0.0.1', '::1' => cfg\CfgLocal::class, // Local (IPv4 ou IPv6)
})::init();

// Router la requête du client.
try {
    Router::route();
} catch (Exception $e) {
    exit($e->getMessage());
}
