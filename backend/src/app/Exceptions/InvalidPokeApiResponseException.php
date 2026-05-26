<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidPokeApiResponseException extends RuntimeException
{
    public function __construct(string $pokemonName)
    {
        parent::__construct("A resposta da PokéAPI para '{$pokemonName}' está em um formato inesperado.");
    }
}
