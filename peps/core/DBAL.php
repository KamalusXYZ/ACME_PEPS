<?php

namespace peps\core;

use PDO;
use PDOException;

/**
 * DBAL via PDO
 * classe 100% static.
 * Design pattern Singleton.
 */
final class DBAL
{
    /**
     * @var PDO|null Instance singleton de PDO.
     */
    private static ?PDO $pdo = null;

    /**
     * Constructeur privé.
     */
    private function __construct()
    {
    }

    /**
     * Crée l'instance singleton de PDO.
     *
     * @return void
     * @throws PDOException Si erreur lors de la connexion.
     */
    public static function init(): void
    {
        if (!self::$pdo) {
            $dsn = 'mysql:dbname=' . Cfg::DB_NAME . ';host=' . Cfg::DB_HOST . ';port=' . Cfg::DB_PORT . ';charset=' . Cfg::DB_CHARSET;
            self::$pdo = new PDO($dsn, Cfg::DB_LOG, Cfg::DB_PWD, Cfg::DB_OPTIONS);

        }
    }

    /**
     * Retourne l'instance singleton de PDO.
     * La méthode init() DOIT avoir était appelée auparavent.
     * @return PDO|null
     */

    public static function getPDO(): ?PDO
    {
        return self::$pdo;
    }
}