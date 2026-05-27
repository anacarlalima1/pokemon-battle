<?php

namespace App\Services;

use App\DTOs\BattleDTO;
use App\DTOs\BattleResultDTO;
use App\DTOs\PokemonDTO;

class BattleService
{
    public function __construct(
        private readonly PokemonService $pokemonService,
    ) {}

    public function execute(string $pokemonOneName, string $pokemonTwoName): BattleDTO
    {
        $pokemonOne = $this->pokemonService->findByName($pokemonOneName);
        $pokemonTwo = $this->pokemonService->findByName($pokemonTwoName);

        return new BattleDTO(
            pokemonOne: $pokemonOne,
            pokemonTwo: $pokemonTwo,
            result: $this->resolveBattleResult($pokemonOne, $pokemonTwo),
        );
    }

    private function resolveBattleResult(PokemonDTO $pokemonOne, PokemonDTO $pokemonTwo): BattleResultDTO
    {
        if ($pokemonOne->hp === $pokemonTwo->hp) {
            return new BattleResultDTO(
                type: 'draw',
                winner: null,
                winnerName: null,
                message: "A batalha terminou empatada. Ambos possuem {$pokemonOne->hp} HP.",
            );
        }

        if ($pokemonOne->hp > $pokemonTwo->hp) {
            return new BattleResultDTO(
                type: 'winner',
                winner: 'pokemon_one',
                winnerName: $pokemonOne->name,
                message: "{$pokemonOne->name} venceu com {$pokemonOne->hp} HP contra {$pokemonTwo->hp} HP.",
            );
        }

        return new BattleResultDTO(
            type: 'winner',
            winner: 'pokemon_two',
            winnerName: $pokemonTwo->name,
            message: "{$pokemonTwo->name} venceu com {$pokemonTwo->hp} HP contra {$pokemonOne->hp} HP.",
        );
    }
}
