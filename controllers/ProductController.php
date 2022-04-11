<?php

declare(strict_types=1);

namespace controllers;

use cfg\CfgApp;
use entities\Category;
use entities\Product;
use Exception;
use peps\core\Cfg;
use peps\core\DBAL;
use peps\core\DBALException;
use peps\core\ORMDB;
use peps\core\Router;
use peps\core\RouterException;
use peps\upload\Upload;
use peps\upload\UploadException;

/**
 * Classe 100% statique de gestion des produits.
 *
 * @see Product
 */
final class ProductController
{
    const ERR_INVALID_NAME = "Nom invalide.";
    const ERR_INVALID_REF = "Référence invalide.";
    const ERR_PERSISTANCE_REF = "Catégorie invalide ou référence déja éxistante.";
    const ERR_INVALID_PRICE = "Prix invalide.";

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
     * @throws \peps\core\RouterException
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

    /**
     * Affiche le formulaire d'ajout d'un produit.
     *
     * GET /product/create/{idCategory}
     * @throws RouterException
     * @return void
     */
    public static function create(array $params):void
    {
        // Récupérer l'idCategory pour caler le menu déroulant.
        $idCategory = (int)$params['idCategory'];
        //Créer un produit
        $product = new Product();
        //Définir son idCategory pour caler le menu déroulant.
        $product->idCategory = $idCategory;
        // Récupérer les catégories pour peupler le menu déroulant.
        $categories = Category::findAllBy([],['name'=>'ASC']);

        // Rendre la vue.
        Router::render('editProduct.php', ['categories'=>$categories, 'product'=> $product]);

    }
    /**
     * Affiche le formulaire de mise à jour d'un produit.
     *
     * GET /product/create/{idProduct}
     * @throws RouterException
     * @return void
     */
    public static function update(array $params):void
    {
        // Récuperer idProduct
        $idProduct = (int)$params['idProduct'];

        // Créer le produit correspondant
        $product = new Product($idProduct);

        // Hydrater le produit. Si produit inéxistant, rediriger vers la vue noProduct.
        if(!$product->hydrate())
            Router::render('noProduct.php');

        // Ajouter dynamiquement la propriété formattedPrice
        $product->formattedPrice = Cfg::get('NF_US_2DEC')->format($product->price);

        // Récupérer les catégories pour peupler le menu déroulant.
        $categories = Category::findAllBy([],['name'=>'ASC']);

        // Rendre la vue.
        Router::render('editProduct.php', ['categories'=>$categories, 'product'=> $product]);

    }

    /**
     * @return void
     *
     */
    public static function save(): void
    {
        try {
            $upload = new Upload('photo', 10000000, ['image/jpeg'],false);
            if($upload->complete) {
                $upload->save(Cfg::get('imgProductsDir'). "/product_99.jpg");
            }
        }
        catch (UploadException $e){
            exit($e->getMessage());

        }




        //Créer un produit.
        $product = new Product();

        // Initialiser le tableau des messages d'erreur.
        $errors = [];

        // Récuperer les données POST
        $product->idProduct = filter_input(INPUT_POST,'idProduct', FILTER_VALIDATE_INT) ?: null;
        $product->idCategory = filter_input(INPUT_POST,'idCategory', FILTER_VALIDATE_INT) ?: null;
        $product->name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
        $product->ref = filter_input(INPUT_POST,'ref', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
        $product->price = filter_input(INPUT_POST,'price', FILTER_VALIDATE_FLOAT) ?: null;

        // Vérifier le nom.(obligatoire et max 50charactères)
        if(!$product->name || mb_strlen($product->name) > 50 )
            $errors[]= self::ERR_INVALID_NAME;

        //Vérifier la réference (obligatoire et max 10 charactères.)
        if(!$product->ref || mb_strlen($product->ref) > 10 )
            $errors[]= self::ERR_INVALID_REF;

        //Vérifier la prix (obligatoire et <=0 ou si  <= 10000.)
        if(!$product->price || $product->price <= 0 || $product->price >= 10000 )
            $errors[]= self::ERR_INVALID_PRICE;

        if(!$errors) {
            try {
                $product->persist();
            }catch(DBALException) {
                $errors[] = self::ERR_PERSISTANCE_REF;
            }

        }
        if($errors){
            $product->formattedPrice = $product->price ? Cfg::get('NF_US_2DEC')->format($product->price) : null;
            $categories = Category::findAllBy([],['name'=>'ASC']);
            Router::render('editProduct.php', ['categories'=>$categories,'product'=>$product,'errors'=>$errors]);
        }
        Router::redirect('/product/list');

    }

    /**
     * Supprime un produit et/ou ses images.
     *     * @param array $params Tableau associatif des paramètres.
     * GET /product/delete/{idProduct}/{all | img}
     *
     * @return void
     * @throws DBALException
     */
    public static function delete(array $params): void
    {
        // Récupérer idProduct et mode.
        $idProduct = (int)$params['idProduct'];
        $mode = $params['mode'];
        // Si mode all, supprimer le produit.
        if($mode === 'all')
            (new Product($idProduct))->remove();
        // Supprimer les images.
        @unlink(Cfg::get('imgProductsDir')."/product_{$idProduct}_small.jpg");
        @unlink(Cfg::get('imgProductsDir')."/product_{$idProduct}_big.jpg");

        // Redirigier vers la liste des produits.
        Router::redirect('/product/list');
    }

}
