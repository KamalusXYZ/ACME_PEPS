<?php

declare(strict_types=1);

namespace peps\core;

use Exception;

/**
 * Classe 100% statique d'autoload.
 */
final class Autoload
{
    /**
     * Constructeur privé.
     */
    private function __construct()
    {
    }

    /**
     * Initialise l'autoload.
     * DOIT être appelée depuis le contrôleur frontal EN TOUT PREMIER.
     * Utilisation de @ devant include pour bypasser les chemins virtuels d'éventuels archives PHAR (PHPUnit...).
     *
     * @throws Exception Si variable serveur SCRIPT_FILENAME indéfinie.
     */
    public static function init(): void
    {
        // Récupération du xxx
        $scriptFilename = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$scriptFilename)
            throw new Exception("Server variable SCRIPT_FILENAME undefined.");
        $path = mb_substr($scriptFilename, 0, mb_strlen($scriptFilename) - 9);
        //var_dump($path);
        // Inscrire la fonction d'autolad dans la pile d'autoload.
        spl_autoload_register(function ($className) {
            global $path;
            //var_dump($className);
            $classPath = strtr($path . strtr($className, '\\', DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR) . '.php';
            //var_dump($classPath);
            @include $classPath;
        });
    }
}
