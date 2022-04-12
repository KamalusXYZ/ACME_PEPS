<?php

declare(strict_types=1);

namespace peps\core;

use Error;

/**
 * Classe de base des entités.
 */
class Entity
{
    /**
     * Invoque la méthode $this->get{ucfirst($name)}() si existante et retourne son résultat.
     * Sinon, retourne null.
     *
     * @param string $name Nom de la propriété inaccessible ou inexistante.
     * @return mixed Retour de la méthode correspondante de $this.
     */
    public function __get(string $name): mixed
    {
        // Construire le nom de la méthode à invoquer.
        $methodName = 'get' . ucfirst($name);
        // Tenter de l'invoquer.
        try {
            return $this->$methodName();
        } catch (Error) {
            return null;
        }
    }
}
