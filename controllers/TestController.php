<?php

declare(strict_types=1);

namespace controllers;

use Exception;
use peps\core\DBAL;
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

        $q = "SELECT * FROM product";

            var_dump(DBAL::get()->xeq($q)->findAll('Productsss'));



    }
}
