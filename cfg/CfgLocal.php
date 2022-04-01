<?php

declare(strict_types=1);

namespace cfg;

use peps\core\Cfg;

/**
 * Classe 100% statique de configuration de l'application pour le serveur local.
 * FINAL parce que dernier niveau d'héritage.
 *
 * @see CfgApp
 */
final class CfgLocal extends CfgApp
{
    /**
     * Constucteur privé.
     *
     *
     */
    private function __construct()
    {
    }

    /**
     * Initialise la configuration.
     * PUBLIC parce que dernier niveau d'héritage.
     *
     * @return void
     */
    public static function init(): void
    {
        // Initialiser la classe parente.
        parent::init();

        // Driver PDO de la DB.
        self::register('dbDriver', 'mysql');

        // Hôte de la DB.
        self::register('dbHost', 'localhost');

        // Port de l'hôte.
        self::register('dbPort', 3306);

        // Nom de la DB.
        self::register('dbName', 'acme');

        // Identifiant de l'utilisateur de la DB.
        self::register('dbLog', 'root');

        // Mot de passe de l'utilisateur de la DB.
        self::register('dbPwd', '');

        // Jeu de caractères de la DB.
        self::register('dbCharset', 'utf8mb4');
    }
}
