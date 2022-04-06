<?php

declare(strict_types=1);

namespace controllers;

use entities\Category;
use entities\Product;
use Exception;
use peps\core\Cfg;
use peps\core\DBAL;
use peps\core\ORMDB;
use peps\core\Router;

/**
 * Classe 100% statique de gestion des produits.
 *
 * @see Product
 */
final class ProductController
{
    /**
     * Constructeur privé.
     */
    private function __construct()
    {
    }

    /**
     * Affiche les informations d'un produit  .
     *
     * GET /test/{id}
     *
     */
    public static function list():void
    {
        // Récupérer les catégories dans l'ordre alphabétique.
        $categories = Category::findAllBy([],['name'=>'ASC']);
        // Rendre la vue.
        Router::render('listProducts.php', ['categories'=>$categories]);
    }

    /**
     * Affiche le détail d'un produit.
     *
     * GET / product/show/{idProduct}
     *
     * @param array $params Tableau associatif des paramètres.
     * @return void
     */
    public static function show($params):void
    {
        // Récuperer idProduct.
        $idProduct = (int)$params['idProduct'];
        // Créer le produit
        $product = new Product($idProduct);
        // Hydrater et si produit inéxistant
        if (!$product->hydrate())
        Router::render('noProduct.php');
        // Ajouter dynamiquement la propriété formattedprice.
        $product->formattedPrice = Cfg::get('NF_LOCALE_2DEC')->format($product->price). ' ' .Cfg::get('appCurrency');
        //Rendre la vue.
        Router::render('showProduct.php', ['product'=>$product]);
    }
}
