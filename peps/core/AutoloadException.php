<?php

declare(strict_types=1);

namespace peps\core;

use Exception;

/**
 * Exceptions en lien avec Autoload.
 * Classe 100% statique.
 *
 * @see Autoload
 * @see Exception
 * @copyright 2020-2022 Gilles VANDERSTRAETEN gillesvds@adok.info
 */
class AutoloadException extends Exception
{
	// Messages d'erreur.
	public const SCRIPT_FILENAME_UNDEFINED = "Server variable script filname undefined.";
}
