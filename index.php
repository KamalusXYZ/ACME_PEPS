<?php

declare(strict_types=1);

use peps\core\Autoload;
use peps\core\Cfg;
use peps\core\DBAL;
use peps\core\Router;
use peps\core\DBALException;
use peps\core\AutoloadException;
use peps\core\SessionDB;



// Initialiser l'autoload (à faire EN PREMIER).
require 'peps/core/Autoload.php';
try {
    Autoload::init();
} catch (AutoloadException $e) {
    exit($e->getMessage());
}


// Initialiser la configuration en fonction de l'IP du serveur (à faire en DEUXIEME).
$serverIP = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_VALIDATE_IP) ?: filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP);
if (!$serverIP)
    exit("Server variable SERVER_ADDR unavailable.");
// ICI VOS CLASSES DE CONFIGURATION EN FONCTION DES ADRESSES IP DE VOS SERVEURS.

(match ($serverIP) {
    '127.0.0.1', '::1' => \cfg\CfgLocal::class, // Local server (IPv4 ou IPv6)
})::init();

// Initialiser la connexion à la DB.(à faire avant d'initialiser la gestion des sessions.
try {
    DBAL::init(
        Cfg::get('dbDriver'),
        Cfg::get('dbHost'),
        Cfg::get('dbPort'),
        Cfg::get('dbName'),
        Cfg::get('dbLog'),
        Cfg::get('dbPwd'),
        Cfg::get('dbCharset')

    );

} catch(DBALException $e){
    exit($e->getMessage());
}

// Initialiser la gestion des sessions (à faire APRES l'initialisation de la connexion à la DB.
try {
    SessionDB::init(
        Cfg::get('sessionTimeout'),
        Cfg::get('sessionMode'),
        Cfg::get('sessionCookieSameSite'),
    );
} catch(DBALException $e) {
    exit($e->getMessage());
}


// Router la requête du client.
try {
    Router::route();
} catch (Exception $e) {
    exit($e->getMessage());
}

