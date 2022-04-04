<?php

declare(strict_types=1);

namespace controllers;

use entities\Product;
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
        // TODO: TESTER METHODE GET
        $dbal = DBAL::get()->start();
        $dbal->savepoint('Test');
        $q2 = "INSERT INTO product VALUES (DEFAULT ,1, 'Test2', 'TEST2', 123.45)";
        var_dump($dbal->xeq($q2)->pk());
        $dbal->rollback();


    }
}
