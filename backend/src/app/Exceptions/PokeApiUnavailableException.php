<?php

namespace App\Exceptions;

use RuntimeException;

class PokeApiUnavailableException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Não foi possível consultar a PokéAPI no momento. Tente novamente mais tarde.');
    }
}
