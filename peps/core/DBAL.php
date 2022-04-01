<?php

declare(strict_types=1);

namespace peps\core;

use Error;
use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * DBAL (via PDO).
 * Design Pattern Singleton.
 *
 * @copyright 2020-2022 Gilles VANDERSTRAETEN gillesvds@adok.info
 */
final class DBAL
{
    /**
     * Options de connexion communes à toutes les DB :
     *   - Gestion des erreurs basée sur des exceptions.
     *   - Typage des colonnes respecté.
     *   - Requêtes réellement préparées plutôt que simplement simulées.
     */
    private const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    /**
     * Instance Singleton.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Instance de PDO.
     *
     * @var PDO|null
     */
    private ?PDO $db = null;

    /**
     * Instance de PDOStatement.
     *
     * @var PDOStatement|null
     */
    private ?PDOStatement $stmt = null;

    /**
     * Nombre d'enregistrements retrouvés (SELECT) ou affectés (NON SELECT) par la dernière requête.
     *
     * @var int|null
     */
    private ?int $nb = null;

    /**
     * Constructeur privé.
     */
    private function __construct()
    {
    }

    /**
     * Crée l'instance Singleton et l'instance PDO encapsulée.
     *
     * @param string $driver Driver DB.
     * @param string $host Hôte DB.
     * @param integer $port Port de l'hôte DB.
     * @param string $dbName Nom de la base de données.
     * @param string $log Identifiant de l'utilisateur DB.
     * @param string $pwd Mot de passe de l'utilisateur DB.
     * @param string $charset Jeu de caractères.
     * @return void
     * @throws DBALException Si la connexion PDO échoue.
     */
    public static function init(
        string $driver,
        string $host,
        int    $port,
        string $dbName,
        string $log,
        string $pwd,
        string $charset
    ): void
    {
        // Si déjà initialisée, ne rien faire.
        if (self::$instance)
            return;
        // Créer la chaîne DSN.
        $dsn = "{$driver}:host={$host};port={$port};dbname={$dbName};charset={$charset}";
        // Créer l'instance Singleton.
        self::$instance = new self();
        // Créer l'instance PDO.
        try {
            self::$instance->db = new PDO($dsn, $log, $pwd, self::OPTIONS);
        } catch (PDOException) {
            throw new DBALException(DBALException::DB_CONNECTION_FAILED);
        }
    }

    /**
     * Retourne l'instance Singleton.
     * La méthode init() DEVRAIT avoir été appelée au préalable.
     *
     * @return self|null Instance Singleton ou null si init() pas encore appelée.
     */
    public static function get(): ?self
    {
        return self::$instance;
    }

    /**
     * Exécute une requête SQL.
     * Par sécurité, si requête NON-SELECT non préparée, l'instance de PDOStatement est remise à null.
     * Retourne $this pour chaînage.
     *
     * @param string $q Requête SQL.
     * @param array|null $params Tableau associatifs des paramètres (optionnel).
     * @return self $this pour chaînage.
     * @throws DBALException Si requête ou paramètres SQL incorrects.
     */
    public function xeq(string $q, ?array $params = null): self
    {
        // Si paramètres présents...
        if ($params) {
            // Préparer et exécuter la requête.
            try {
                $this->stmt = $this->db->prepare($q);
            } catch (PDOException) {
                throw new DBALException(DBALException::WRONG_PREPARED_SQL_QUERY);
            }
            try {
                $this->stmt->execute($params);
            } catch (PDOException) {
                throw new DBALException(DBALException::WRONG_SQL_QUERY_PARAMETERS);
            }
            // Récupérer le nombre d'enregistrements retrouvés ou affectés.
            $this->nb = $this->stmt->rowCount();
        } // Sinon, si requête SELECT, l'exécuter sans préparation.
        elseif (mb_stripos(ltrim($q), 'SELECT') === 0) {
            // Récupérer le nombre d'enregistrements retrouvés.
            try {
                $this->stmt = $this->db->query($q);
            } catch (PDOException) {
                throw new DBALException(DBALException::WRONG_SELECT_SQL_QUERY);
            }
            $this->nb = $this->stmt->rowCount();
        } // Sinon (requête NON SELECT), l'executer et récupérer le nombre d'enregistrements affectés.
        else {
            try {
                $this->nb = $this->db->exec($q);
            } catch (PDOException) {
                throw new DBALException(DBALException::WRONG_NON_SELECT_SQL_QUERY);
            }
            // Par sécurité, remettre à null l'instance de PDOStatement.
            $this->stmt = null;
        }
        // Retourner $this.
        return $this;
    }

    /**
     * Retourne le nombre d'enregistrements retrouvés (SELECT) ou affectés (NON-SELECT) par la dernière requête exécutée.
     *
     * @return int|null Le nombre d'enregistrements.
     */
    public function nb(): ?int
    {
        return $this->nb;
    }

    /**
     * Retourne un tableau d'instances d'une classe donnée en exploitant le dernier jeu d'enregistrements.
     * Une requête SELECT DEVRAIT avoir été exécutée préalablement.
     *
     * @param string $className La classe donnée.
     * @return array Tableau d'instances de la classe donnée.
     * @throws DBALException Si classe inexistante.
     */
    public function findAll(string $className = 'stdClass'): array
    {
        // Si pas de recordset, retourner un tableau vide.
        if (!$this->stmt)
            return [];
        // Sinon, exploiter le recordset et retourner un tableau d'instances.
        try {
            $this->stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
        } catch (Error) {
            throw new DBALException(DBALException::FETCH_CLASS_UNAVAILABLE);
        }
        return $this->stmt->fetchAll();
    }

    /**
     * Retourne une instance d'une classe donnée en exploitant le premier des enregistrements du dernier jeu.
     * Une requête SELECT (typiquement retrouvant au maximum un enregistrement) DEVRAIT avoir été exécutée préalablement.
     * Retourne null si aucun recordset ou recordset vide.
     *
     * @param string $className La classe donnée.
     * @return object|null L'instance de la classe donnée ou null.
     */
    public function findOne(string $className = 'stdClass'): ?object
    {
        // Si pas de recordset, retourner null.

        // Sinon, exploiter le recordset et retourner la première instance ou null.

        //TEMP
        return null;
    }

    /**
     * Hydrate une instance donnée en exploitant le premier enregistrement du dernier jeu.
     * Une requête SELECT (typiquement retrouvant au maximum un enregistrement) DEVRAIT avoir été exécutée préalablement.
     *
     * @param object $obj Instance donnée à hydrater.
     * @return boolean True si l'hydratation a réussi.
     */
    public function into(object $obj): bool
    {
        // Si pas de recordset, retourner false.

        // Sinon, exploiter le recordset et hydrater l'instance.

        //TEMP
        return true;
    }

    /**
     * Retourne la dernière PK auto-incrémentée.
     *
     * @return integer PK
     */
    public function pk(): int
    {
        //TEMP
        return 0;
    }

    /**
     * Démarre une transaction.
     * Retourne $this pour chaînage.
     *
     * @return self $this pour chaînage.
     */
    public function start(): self
    {
        //TEMP
        return $this;
    }

    /**
     * Définit un point de restauration dans la transaction en cours.
     * Retourne $this pour chaînage.
     *
     * @param string $label Nom du point de restauration.
     * @return self $this pour chaînage.
     */
    public function savepoint(string $label): self
    {
        //TEMP
        return $this;
    }

    /**
     * Effectue un rollback au point de restauration donné ou au départ si absent.
     * Retourne $this pour chaînage.
     *
     * @param string|null $label Nom du point de restauration (optionnel).
     * @return self $this pour chaînage.
     */
    public function rollback(?string $label = null): self
    {
        //TEMP
        return $this;
    }

    /**
     * Valide la transaction en cours.
     * Retourne $this pour chaînage.
     *
     * @return self $this pour chaînage.
     */
    public function commit(): self
    {
        //TEMP
        return $this;
    }
}
