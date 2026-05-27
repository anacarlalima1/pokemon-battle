<?php

namespace Tests\Unit\Mappers;

use App\Exceptions\InvalidPokeApiResponseException;
use App\Mappers\PokemonMapper;
use PHPUnit\Framework\TestCase;

class PokemonMapperTest extends TestCase
{
    public function test_it_maps_pokeapi_response_to_pokemon_dto(): void
    {
        $mapper = new PokemonMapper();

        $pokemon = $mapper->mapToDTO([
            'name' => 'pikachu',
            'stats' => [
                [
                    'base_stat' => 35,
                    'stat' => [
                        'name' => 'hp',
                    ],
                ],
            ],
            'sprites' => [
                'front_default' => 'pikachu.png',
                'other' => [
                    'showdown' => [
                        'front_default' => 'pikachu.gif',
                    ],
                    'official-artwork' => [
                        'front_default' => 'pikachu-artwork.png',
                    ],
                ],
            ],
            'types' => [
                [
                    'type' => [
                        'name' => 'electric',
                    ],
                ],
            ],
        ], 'pikachu');

        $this->assertSame('pikachu', $pokemon->name);
        $this->assertSame(35, $pokemon->hp);
        $this->assertSame('pikachu.png', $pokemon->sprite);
        $this->assertSame('pikachu.gif', $pokemon->animatedSprite);
        $this->assertSame(['electric'], $pokemon->types);
    }

    public function test_it_throws_exception_when_response_is_not_array(): void
    {
        $this->expectException(InvalidPokeApiResponseException::class);

        $mapper = new PokemonMapper();

        $mapper->mapToDTO(null, 'pikachu');
    }

    public function test_it_throws_exception_when_name_is_missing(): void
    {
        $this->expectException(InvalidPokeApiResponseException::class);

        $mapper = new PokemonMapper();

        $mapper->mapToDTO([
            'stats' => [
                [
                    'base_stat' => 35,
                    'stat' => [
                        'name' => 'hp',
                    ],
                ],
            ],
        ], 'pikachu');
    }

    public function test_it_throws_exception_when_hp_is_missing(): void
    {
        $this->expectException(InvalidPokeApiResponseException::class);

        $mapper = new PokemonMapper();

        $mapper->mapToDTO([
            'name' => 'pikachu',
            'stats' => [],
            'sprites' => [],
            'types' => [],
        ], 'pikachu');
    }

    public function test_it_uses_fallback_sprite_when_front_default_is_missing(): void
    {
        $mapper = new PokemonMapper();

        $pokemon = $mapper->mapToDTO([
            'name' => 'pikachu',
            'stats' => [
                [
                    'base_stat' => 35,
                    'stat' => [
                        'name' => 'hp',
                    ],
                ],
            ],
            'sprites' => [
                'front_default' => null,
                'other' => [
                    'official-artwork' => [
                        'front_default' => 'pikachu-artwork.png',
                    ],
                ],
            ],
            'types' => [],
        ], 'pikachu');

        $this->assertSame('pikachu-artwork.png', $pokemon->sprite);
    }
}
