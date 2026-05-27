<?php

namespace App\Services;

use App\Integrations\PokeApi;
use App\DTOs\PokemonDTO;
use App\Mappers\PokemonMapper;

class PokemonService
{
    public function __construct(
        private readonly PokeApi $pokeApi,
        private readonly PokemonMapper $pokemonMapper,
    ) {
    }

    public function findByName(string $name): PokemonDTO
    {
        $data = $this->pokeApi->getPokemonByName($name);

        return $this->pokemonMapper->mapToDTO($data, $name);
    }
}
