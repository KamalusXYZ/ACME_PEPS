<?php

declare(strict_types=1);

namespace controllers;

use entities\Product;
use Exception;
use peps\core\DBAL;
use peps\core\ORMDB;
use peps\core\Router;

/**
 * Classe 100% statique de test.
 */
final class TestController
{
    /**
     * Constructeur privé.
     */
    private function __construct()
    {
    }

    /**
     * Méthode de test.
     *
     * GET /test/{id}
     *
     * @param array $params Tableau associatif des paramètres.
     * @return void
     */
    public static function test(array $params):void {
    $product = new Product(1);
    var_dump($product->getImgPath('small'));

    }
}
