<?php

namespace App\Exceptions;

use RuntimeException;

class PokemonNotFoundException extends RuntimeException
{
    public function __construct(string $pokemonName)
    {
        parent::__construct("O Pokémon '{$pokemonName}' não foi encontrado.");
    }
}
