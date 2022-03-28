<?php

declare(strict_types=1);

namespace classes;

use Error;

/**
 * Classe de base des entités.
 */
class Entity
{
    /**
     *Invoque la méthode $this->{ucfirst($name)} () si existante et retourne son resultat.
     * Sinon retourne null.
     *
     * @param string $property Nom de la proriété inaccessible ou inexistante.
     * @return mixed Retour de la methode correspondante de $this.
     */
    public function __get(string $property): mixed
    {

        $method = "get" . ucfirst($property);

        try {
            return $this->$method();
        } catch (Error $e) {
            return null;
        }
    }


}