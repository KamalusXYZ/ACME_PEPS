<?php

declare(strict_types=1);

namespace peps\core;

use Locale;
use NumberFormatter;

/**
 * Classe 100% statique de configuration par défaut de l'application.
 * DOIT être étendue dans l'application par une classe ou plusieurs sous-classe de configuration.
 * Typiquement, une classe de configuration générale et des sous-classes par serveur.
 * Extension PHP intl requise.
 */
class Cfg
{
    /**
     * Tableau associatif des constantes de configuration.
     *
     * @var array
     */
    private static array $constants = [];

    /**
     * Constucteur privé.
     */
    private function __construct()
    {
    }

    /**
     * Inscrit les constantes par défaut sous la forme de paires clé/valeur.
     * DOIT être redéfinie dans chaque sous-classe pour y inscrire les constantes de l'application en invoquant parent::init() en première instruction.
     * Cette méthode DOIT être PUBLIC dans la sous-classe sur laquelle elle sera appelée par le contrôleur frontal.
     * Les clés en SNAKE_CASE inscrites ici sont LES SEULES accessibles aux classes Peps.
     * Les clés ajoutées par les sous-classes DEVRAIENT être en camelCase.
     *
     * @return void
     */
    protected static function init(): void
    {
        // Chemin du fichier JSON des routes depuis la racine de l'application.
        self::register('ROUTES_FILE', 'cfg/routes.json');

        // Namespace des contrôleurs.
        self::register('CONTROLLERS_NAMESPACE', '\\controllers');

        // Chemin du répertoire des vues depuis la racine de l'application.
        self::register('VIEWS_DIR', 'views');

        // Nom de la vue affichant l'erreur 404.
        self::register('ERROR_404_VIEW', 'error404.php');

        // Locale par défaut en cas de non détection (ex: 'fr' ou 'fr-FR').
        self::register('LOCALE_DEFAULT', 'fr');

        // Locale du client.
        self::register('LOCALE', (function (): string {
            // Récupérer les locales du clients.
            $locales = filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: filter_var($_SERVER['HTTP_ACCEPT_LANGUAGE'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            return $locales ? Locale::acceptFromHttp($locales) : self::get('LOCALE_DEFAULT');
        })());

        //Instance de NumberFormatter pour formater un nombre avec 2 décimales selon la locale.
        self::register('NF_LOCALE_2DEC', NumberFormatter::create(self::get('LOCALE'), NumberFormatter::PATTERN_DECIMAL, '#,##0.00'));

        //Instance de NumberFormatter pour formater un nombre avec 2 décimales selon la locale us.
        self::register('NF_US_2DEC', NumberFormatter::create('en-us', NumberFormatter::PATTERN_DECIMAL, '0.00'));

        // Durée de vie des sessions (secondes).
        self::register('sessionTimeout', 300); // 5 minutes

        // Mode des sessions (PERSISTENT | HYBRID | ABSOLUTE).
        self::register('sessionMode', Cfg::get('SESSION_HYBRID'));

        // Option cookie_samesite des sessions (STRICT | LAX | NONE).
        self::register('cookieSameSite', Cfg::get('COOKIE_SAMESITE_STRICT'));

        // Constante du mode PERSISTENT des sessions.
        self::register('SESSION_PERSISTENT', 'SESSION_PERSISTENT');

        // Constante du mode HYBRID des sessions.
        self::register('SESSION_HYBRID', 'SESSION_HYBRID');

        // Constante du mode ABSOLUTE des sessions.
        self::register('SESSION_ABSOLUTE', 'SESSION_ABSOLUTE');

        // Constante de l'option STRICT de "cookie_samesite" des sessions.
        self::register('COOKIE_SAMESITE_STRICT', 'STRICT');

        // Constante de l'option LAX de "cookie_samesite" des sessions.
        self::register('COOKIE_SAMESITE_LAX', 'LAX');

        // Constante de l'option NONE de "cookie_samesite" des sessions.
        self::register('COOKIE_SAMESITE_NONE', 'NONE');

        // Longueur du SID.
        self::register('SID_LENGTH', 32);
    }

    /**
     * Inscrit une constante (paire clé/valeur) dans le tableau des constantes.
     *
     * @param string $key Clé.
     * @param mixed $val Valeur.
     * @return void
     */
    protected final static function register(string $key, mixed $val): void
    {
        self::$constants[$key] = $val;
    }

    /**
     * Retourne la valeur de la constante à partir de sa clé.
     * Retourne null si clé inexistante.
     *
     * @param string $key Clé.
     * @return mixed Valeur.
     */
    public final static function get(string $key): mixed
    {
        return self::$constants[$key] ?? null;
    }
}
