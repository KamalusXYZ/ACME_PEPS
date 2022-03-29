<?php

declare(strict_types=1);


namespace controllers;
/**
 *
 *Classe final 100% statique de test
 */
final class TestController
{
    /*
     * Constructeur privé.
     */
    public function __construct()
    {
    }

    /**
     * Méthode de test.
     *
     * GET / /test//{id}
     *
     * @param array $params Tableau associatif des parametres.
     * @return void
     *
     */
    public static function test(array $params): void{

        exit("id = {$params['id']}");


    }

}