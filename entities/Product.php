<?php

declare(strict_types=1);

namespace entities;

use PDO;

/**
 * Entité Product.
 * Toutes les propriétés à null par défaut pour les éventuels formulaires de saisie.
 *
 * @see Entity
 */
class Product extends Entity
{
    /**
     * @var int | null PK.
     */
    public ?int $idProduct = null;

    /**
     * @var int | null FK de la catégorie.
     */
    public ?int $idCategory = null;

    /**
     * @var string | null Nom.
     */
    public ?string $name = null;

    /**
     * @var string | null Référence.
     */
    public ?string $ref = null;

    /**
     * @var float | null Prix.
     */
    public ?float $price = null;

    /**
     * @var Category|null Catégorie de ce produit.
     */
    protected ?Category $category = null;

    /**
     * Constructeur.
     *
     * @param int|null $idProduct PK.
     */
    public function __construct(?int $idProduct = null)
    {
        $this->idProduct = $idProduct;
    }

    /**
     * Retourne la catégorie de ce produit.
     * Lazy loading.
     *
     * @return Category | null La catégorie.
     */
    protected function getCategory(): ?Category
    {
        // Si propriété non renseignée, requêter la DB.
        if (!$this->category) {
            $q = "SELECT * FROM category WHERE idCategory = :idCategory";
            $params = [':idCategory' => $this->idCategory];
            $rs = DBAL::getPDO()->prepare($q);
            $rs->execute($params);
            $rs->setFetchMode(PDO::FETCH_CLASS, Category::class);
            $this->category = $rs->fetch() ?: null;
        }
        return $this->category;
    }

    /**
     * Hydrate le produit en se basant sur sa PK.
     *
     * @return bool Vrai si hydratation réussie. False sinon.
     */
    public function hydrate(): bool
    {
        // Si idProduct invalide, retourner false.
        if (!$this->idProduct) {
            return false;
        }
        // Requêter la DB.
        $q = "SELECT * FROM product WHERE idProduct = :idProduct";
        $params = [':idProduct' => $this->idProduct];
        $rs = DBAL::getPDO()->prepare($q);
        $rs->execute($params);
        $rs->setFetchMode(PDO::FETCH_INTO, $this);
        // Hydrater le produit et retourner le succès ou l'échec.
        return (bool)$rs->fetch();
    }

    /**
     * Retourne le chemin du fichier image (small ou big) associé au produit.
     * Utilise l'image absente si inexistant.
     *
     * @param string $size Constante IMG_SMALL ou IMG_BIG.
     * @return string Chemin.
     */
    public function getImgPath(string $size): string
    {
        $path = Cfg::IMG_ROOT . "product_{$this->idProduct}_{$size}.jpg";
        return file_exists($path) ? $path : Cfg::IMG_ROOT . "product_0_{$size}.jpg";
    }
}
