<?php

namespace entities;

use PDO;

/**
 * Entité Catégorie.
 * Toutes les propriétés à null par défaut pour d'éventuels formulaire de saisi.
 *
 * @see Entity
 */

class Category extends Entity
{
    /**
     *
     * @var int|null PK.
     */
    public ?int $idCategory = null;
    /**
     *
     *
     * @var string|null  Nom.
     */
    public ?string $name = null;
    /**
     *Collection de produits de cette catégorie.
     *
     * @var array|null
     */
    protected ?array $products = null;



    /**
     * Retourne un tableau de produits (triés par nom) de cette catégorie.
     *
     * @return Product[] Tableau de produits.
     */
    protected function getProducts(): array
    {
        if(!$this->products){
            $q = "SELECT * FROM product WHERE idCategory = :idCategory ORDER BY name";
            $params = [':idCategory'=> $this->idCategory];
            $rs = DBAL::getPDO()->prepare($q);
            $rs->execute($params);
            $rs->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Product::class);
            $this->products = $rs->fetchAll();

        }
        return $this->products;
    }
    /**
     * Retourne un tableau de toutes les catégories, triées par nom
     *
     * @return Category[] Les catégories.
     */
    public static function all(): array{
        $q ="SELECT * FROM category ORDER BY name";
        $rs = DBAL::getPDO()->query($q);
        $rs->setFetchMode(PDO::FETCH_CLASS, Category::class);
        return $rs->fetchAll();

    }


}