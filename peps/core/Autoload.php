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
        // Récupération du chemin absolu du contrôleur frontal (index.php). Double tentative nécessaire pour fonctionnement sur tous serveurs.
        $scriptFilename = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //var_dump($scriptFilename);
        // Si introuvable, déclencher une exception.
        if (!$scriptFilename)
            throw new Exception("Server variable SCRIPT_FILENAME undefined.");
        // Définir le chemin absolu du répertoire racine de l'application en soustrayant les 9 derniers caractères (index.php).
        $path = mb_substr($scriptFilename, 0, mb_strlen($scriptFilename) - 9);
        //var_dump($path);
        // Inscrire la fonction d'autolad dans la pile d'autoload.
        spl_autoload_register(fn ($className) => @include strtr($path . strtr($className, '\\', DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR) . '.php');
    }
}
