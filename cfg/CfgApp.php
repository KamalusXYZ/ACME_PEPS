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

        // Devise de l'application.
        self::register('appCurrency', '€');

        // Chemin du répertoire des photos depuis la racine de l'application.
        self::register('imgProductsDir', 'assets/img/products');

        // Chemin du répertoire des photos depuis la racine de l'application.
        self::register('imgDir', 'assets/img');

        // Titre de l'application.
        self::register('appTitle', 'ACME');

        // Poids maxi d'une photo (octets).
        self::register('imgMaxFileSize', 10 * 1024 * 1024); // 10 MB

        // Tableau des types MIME autorisés pour la photo. Vide si tous types autorisés.
        self::register('imgAllowedMimeTypes', ['image/jpeg']);

        // Largeur du cadre de destination des grandes images (pixels).
        self::register('imgBigWidth', 450);

        // Hauteur du cadre de destination des grandes images (pixels).
        self::register('imgBigHeight', 450);

        // Largeur du cadre de destination des petites images (pixels).
        self::register('imgSmallWidth', 300);

        // Hauteur du cadre de destination des petites images (pixels).
        self::register('imgSmallHeight', 300);
    }
}

