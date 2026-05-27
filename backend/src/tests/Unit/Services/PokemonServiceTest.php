<?php

namespace Tests\Unit\Services;

use App\DTOs\PokemonDTO;
use App\Integrations\PokeApi;
use App\Mappers\PokemonMapper;
use App\Services\PokemonService;
use PHPUnit\Framework\TestCase;

class PokemonServiceTest extends TestCase
{
    public function test_it_finds_pokemon_by_name(): void
    {
        $pokeApiData = [
            'name' => 'pikachu',
            'stats' => [],
            'sprites' => [],
            'types' => [],
        ];

        $pokemonDTO = new PokemonDTO(
            name: 'pikachu',
            hp: 35,
            sprite: 'pikachu.png',
            animatedSprite: 'pikachu.gif',
            types: ['electric'],
        );

        $gateway = $this->createMock(PokeApi::class);
        $mapper = $this->createMock(PokemonMapper::class);

        $gateway->expects($this->once())
            ->method('getPokemonByName')
            ->with('pikachu')
            ->willReturn($pokeApiData);

        $mapper->expects($this->once())
            ->method('mapToDTO')
            ->with($pokeApiData, 'pikachu')
            ->willReturn($pokemonDTO);

        $service = new PokemonService(
            pokeApi: $gateway,
            pokemonMapper: $mapper,
        );

        $pokemon = $service->findByName('pikachu');

        $this->assertSame($pokemonDTO, $pokemon);
        $this->assertSame('pikachu', $pokemon->name);
        $this->assertSame(35, $pokemon->hp);
        $this->assertSame(['electric'], $pokemon->types);
    }
}
