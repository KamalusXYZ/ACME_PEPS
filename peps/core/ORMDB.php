<?php

declare(strict_types=1);

namespace peps\core;

use ReflectionClass;
use ReflectionProperty;

/**
 * Implémentation de la persistance ORM en DB via DBAL.
 * Les classes entités DEVRAIENT étendre cette classe.
 * *********************************************************
 * Règles à respecter pour profiter de cette implémentation.
 * Sinon, redéfinir ces méthodes dans les classes entités.
 * *********************************************************
 * -1- Tables nommées selon cet exemple: classe 'TrucChose', table 'trucChose'.
 * -2- PK auto-incrémentée nommée selon cet exemple: table 'trucChose', PK 'idTrucChose'.
 * -3- Chaque colonne correspond à une propriété PUBLIC du même nom. Les autres propriétés NE sont PAS PUBLIC.
 * 
 * @see ORM
 * @copyright 2020-2022 Gilles VANDERSTRAETEN gillesvds@adok.info
 */
class ORMDB extends ORM
{
	/**
	 * {@inheritDoc}
	 */
	public function hydrate(): bool
	{
		// Récupérer le nom court (pas pleinement qualifié) de la classe de l'entité $this pour en déduire le nom de la table.
        $classname = (new ReflectionClass($this))->getShortName();
        $tablename = lcfirst($classname);
        // Construire le nom de la PK à partir du nom de la classe.
        $pkName = "id{$classname}";
		// Exécuter la requête et hydrater $this.
        $q = "SELECT * FROM {$tablename} WHERE $pkName = :__ID__";
        $params = ['__ID__'=> $this->$pkName ];
        return DBAL::get()->xeq($q, $params)->into($this);
	}

	/**
	 * {@inheritDoc}
	 */
	public function persist(): bool
	{
		// Récupérer le nom court (pas pleinement qualifié) de la classe de l'entité $this pour en déduire le nom de la table.
        $rc = new ReflectionClass($this);
        $classname = $rc->getShortName();
        $tablename = lcfirst($classname);
		// Construire le nom de la PK à partir du nom de la classe.
        $pkName = "id{$classname}";

		// Récupérer le tableau des propriétés publiques de la classe.
        $properties = $rc->getProperties(ReflectionProperty::IS_PUBLIC);

		// Initialiser les éléments de requêtes et le tableau des paramètres.
        $strInsertColumns = $strInsertValues = $strUpdate = '';
        $params = [];

		// Pour chaque propriété, construire les éléments des requêtes SQL et compléter les paramètres.
        foreach ($properties as $property){
            $params[]= $property;
            $strInsertColumns = "INSERT INTO $classname  ($pkName) VALUES ($$pkName) ";

            $strInsertColumns = "UPDATE $classname SET $property = x WHERE id = $pkName";



        }

		// Supprimer la dernière virgule de chaque élément de requête.

		// Créer les requêtes et les paramètres SQL.

		// Exécuter la requête INSERT ou UPDATE et, si INSERT, récupérer la PK auto-incrémentée.

		// Retourner true systématiquement.
        return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove(): bool
	{
		// Récupérer le nom court (pas pleinement qualifié) de la classe de l'entité $this pour en déduire le nom de la table.

		// Construire le nom de la PK à partir du nom de la classe.

		// Si PK non renseignée, retourner false.

		// Exécuter la requête et retourner la conclusion.

        //TEMP
        return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function findAllBy(array $filters = [], array $sortKeys = [], string $limit = ''): array
	{
		// Récupérer le nom court (pas pleinement qualifié) de la classe de l'entité $this pour en déduire le nom de la table.

		// Initialiser les requêtes SQL et les paramètres SQL.

        // Si filtres présents...

            // Construire la clause WHERE.

			// Supprimer le dernier ' AND'.

        // Si clés de tri présents...

			// Construire la clause ORDER BY.

			// Supprimer la dernière virgule.

        // Si limite, ajouter la clause LIMIT.

		// Exécuter la requête et retourner le tableau.

        //TEMP
        return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function findOneBy(array $filters = []): ?ORM
	{

        //TEMP
        return null;
	}
}
