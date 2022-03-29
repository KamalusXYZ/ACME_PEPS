<?php

declare(strict_types=1);

use peps\core\Autoload;
use peps\core\Router;

//Initialiser l'autoload (à faire en premier).
require 'peps/core/Autoload.php';

try{

    Autoload::init();
} catch (Exception $e ){
    exit($e->getMessage());
}

//Router la requete du client.
Router::route();
