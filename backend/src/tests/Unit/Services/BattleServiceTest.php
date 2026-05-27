<?php

namespace Tests\Unit\Services;

use App\DTOs\PokemonDTO;
use App\Services\BattleService;
use App\Services\PokemonService;
use PHPUnit\Framework\TestCase;

class BattleServiceTest extends TestCase
{
    public function test_it_returns_pokemon_one_as_winner(): void
    {
        $pokemonService = $this->createMock(PokemonService::class);

        $pokemonService->method('findByName')
            ->willReturnMap([
                [
                    'snorlax',
                    new PokemonDTO(
                        name: 'snorlax',
                        hp: 160,
                        sprite: null,
                        animatedSprite: null,
                        types: ['normal']
                    ),
                ],
                [
                    'pikachu',
                    new PokemonDTO(
                        name: 'pikachu',
                        hp: 35,
                        sprite: null,
                        animatedSprite: null,
                        types: ['electric']
                    ),
                ],
            ]);

        $service = new BattleService($pokemonService);

        $battle = $service->execute('snorlax', 'pikachu');

        $this->assertSame('snorlax', $battle->pokemonOne->name);
        $this->assertSame('pikachu', $battle->pokemonTwo->name);
        $this->assertSame('winner', $battle->result->type);
        $this->assertSame('pokemon_one', $battle->result->winner);
        $this->assertSame('snorlax', $battle->result->winnerName);
    }

    public function test_it_returns_pokemon_two_as_winner(): void
    {
        $pokemonService = $this->createMock(PokemonService::class);

        $pokemonService->method('findByName')
            ->willReturnMap([
                [
                    'pikachu',
                    new PokemonDTO(
                        name: 'pikachu',
                        hp: 35,
                        sprite: null,
                        animatedSprite: null,
                        types: ['electric']
                    ),
                ],
                [
                    'charizard',
                    new PokemonDTO(
                        name: 'charizard',
                        hp: 78,
                        sprite: null,
                        animatedSprite: null,
                        types: ['fire', 'flying']
                    ),
                ],
            ]);

        $service = new BattleService($pokemonService);

        $battle = $service->execute('pikachu', 'charizard');

        $this->assertSame('winner', $battle->result->type);
        $this->assertSame('pokemon_two', $battle->result->winner);
        $this->assertSame('charizard', $battle->result->winnerName);
    }

    public function test_it_returns_draw_when_both_pokemons_have_same_hp(): void
    {
        $pokemonService = $this->createMock(PokemonService::class);

        $pokemonService->method('findByName')
            ->willReturnMap([
                [
                    'ditto',
                    new PokemonDTO(
                        name: 'ditto',
                        hp: 48,
                        sprite: null,
                        animatedSprite: null,
                        types: ['normal']
                    ),
                ],
                [
                    'jigglypuff',
                    new PokemonDTO(
                        name: 'jigglypuff',
                        hp: 48,
                        sprite: null,
                        animatedSprite: null,
                        types: ['normal', 'fairy']
                    ),
                ],
            ]);

        $service = new BattleService($pokemonService);

        $battle = $service->execute('ditto', 'jigglypuff');

        $this->assertSame('draw', $battle->result->type);
        $this->assertNull($battle->result->winner);
        $this->assertNull($battle->result->winnerName);
    }
}
