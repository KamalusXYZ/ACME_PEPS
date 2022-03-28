<?php

namespace entities;
use PDO;


/**
 * Entité Product
 * Toutes les propriétés à null par défaut pour d'éventuels formulaire de saisi.
 *
 * @see Entity
 *
 */

class Product extends Entity
{
    /**
     *
     * @var int|null PK.
     */
    public ?int $idProduct = null;
    /**
     * @var int|null FK.
     */
    public ?int $idCategory = null;
    /**
     * @var string|null nom.
     */
    public ?string $name = null;
    /**
     * @var string|null rérérence
     */
    public ?string $ref = null;
    /**
     * @var float|null prix.
     */
    public ?float $price = null;
    /**
     * @var category|null Catégorie de ce produit.
     */
    protected ?Category $category = null;

    /**
     * Constructeur.
     *
     * @param int|null $idProduct PK.
     *
     */
    public function __construct(?int $idProduct = null){
            $this->idProduct = $idProduct;

    }


    /**
     * Retourne une instance de catégorie.
     * lazy loading.
     *
     * @return Product[]| null Tableau de produits.
     */
    protected function getCategory(): ?Category
    {
        // Si propriété non renseignée, requeter la DB.
        if(!$this->category){
            $q = "SELECT * FROM category WHERE idCategory = :idCategory";
            $params = [':idCategory'=> $this->idCategory];
            $rs = DBAL::getPDO()->prepare($q);
            $rs->execute($params);
            $rs->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Category::class);
            $this->category = $rs->fetch() ?: null;

        }
        return $this->category;
    }

    /**
     *
     * Retourne le chemin du fichier image (small ou big) associé au produit.
     * Utilise l'image absente si inexistante.
     * @param string $size Constante IMG_SMALL ou IMG_BIG
     * @return string Chemin.
     *
     */

    public function getImgPath(string $size): string {

            $path = Cfg::IMG_ROOT."product_{$this->idProduct}_{$size}.jpg";
             return file_exists($path) ? $path : Cfg::IMG_ROOT . "product_0_{$size}.jpg";

    }

    /**
     * Hydrate le produit en se basant sur sa clé primaire.
     *
     * @return bool vrai si hydration reussie. False sinon.
     *
     */
    public function hydrate(): bool{

        // Si l' idProduct invalide ,retourner false.
        if(!$this->idProduct){
            return false;
        }

        // Requeter la DB.

        $q = "SELECT * FROM product WHERE idProduct = :idProduct";
        $params = [':idProduct' => $this->idProduct];
        $rs = DBAL::getPDO()->prepare($q);
        $rs->execute($params);
        $rs->setFetchMode(PDO::FETCH_INTO, $this);

        //Hydrater le produit et retourner le succes ou l'échec.
        return (bool) $rs->fetch();
    }

}











