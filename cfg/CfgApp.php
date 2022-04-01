<?php

declare(strict_types=1);

namespace cfg;

use peps\core\Cfg;

/**
 * Classe 100% statique de configuration générale de l'application.
 * NON FINAL parce que sous-classes présentes.
 *
 * @see Cfg
 */
class CfgApp extends Cfg
{
    /**
     * Constucteur privé.
     */
    private function __construct()
    {
    }

    /**
     * Initialise la configuration.
     * PROTECTED parce que sous-classes présentes.
     *
     * @return void
     */
    protected static function init(): void
    {
        // Initialiser la classe parente.
        parent::init();

        // Titre de l'application.
        self::register('appTitle', 'ACME');
    }
}
