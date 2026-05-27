<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class BattleDTO implements Arrayable
{
    public function __construct(
        public readonly PokemonDTO $pokemonOne,
        public readonly PokemonDTO $pokemonTwo,
        public readonly BattleResultDTO $result,
    ) {}

    public function toArray(): array
    {
        return [
            'pokemon_one' => $this->pokemonOne->toArray(),
            'pokemon_two' => $this->pokemonTwo->toArray(),
            'result' => $this->result->toArray(),
        ];
    }
}
