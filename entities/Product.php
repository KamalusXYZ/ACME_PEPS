<?php

declare(strict_types=1);

namespace entities;

use peps\core\Cfg;
use peps\core\ORMDB;


/**
 * Entité Product.
 * Toutes les propriétés à null par défaut pour les éventuels formulaires de saisie.
 *
 * @see ORMDB
 */
class Product extends ORMDB
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
        if (!$this->category)
            $this->category = Category::findOneBy(["idCategory"=>$this->idCategory]);

        return $this->category;
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
        $path = Cfg::get('imgProductsDir') . "/product_{$this->idProduct}_{$size}.jpg";
        return '/'. (file_exists($path) ? $path : Cfg::get('imgProductsDir') . "/product_0_{$size}.jpg");
    }
}
