<?php

namespace App\Services;

use App\DTOs\PokemonDTO;

class BattleService
{
    public function __construct(
        private readonly PokemonService $pokemonService,
    ) {
    }

    public function battle(string $pokemonOneName, string $pokemonTwoName): array
    {
        $pokemonOne = $this->pokemonService->findByName($pokemonOneName);
        $pokemonTwo = $this->pokemonService->findByName($pokemonTwoName);

        return [
            'pokemon_one' => $pokemonOne->toArray(),
            'pokemon_two' => $pokemonTwo->toArray(),
            'result' => $this->resolveBattleResult($pokemonOne, $pokemonTwo),
        ];
    }

    private function resolveBattleResult(PokemonDTO $pokemonOne, PokemonDTO $pokemonTwo): array
    {
        if ($pokemonOne->hp === $pokemonTwo->hp) {
            return [
                'type' => 'draw',
                'winner' => null,
                'message' => "A batalha terminou empatada. Ambos possuem {$pokemonOne->hp} HP.",
            ];
        }

        $winner = $pokemonOne->hp > $pokemonTwo->hp ? $pokemonOne : $pokemonTwo;
        $loser = $pokemonOne->hp > $pokemonTwo->hp ? $pokemonTwo : $pokemonOne;

        return [
            'type' => 'winner',
            'winner' => $winner->name,
            'message' => "{$winner->name} venceu com {$winner->hp} HP contra {$loser->hp} HP.",
        ];
    }
}
