<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PokemonBattleTest extends TestCase
{
    public function test_it_returns_pokemon_two_as_winner_when_it_has_more_hp(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pokemonResponse(
                    name: 'pikachu',
                    hp: 35,
                    sprite: 'https://example.com/pikachu.png',
                    animatedSprite: 'https://example.com/pikachu.gif',
                    types: ['electric']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/charizard' => Http::response(
                $this->pokemonResponse(
                    name: 'charizard',
                    hp: 78,
                    sprite: 'https://example.com/charizard.png',
                    animatedSprite: 'https://example.com/charizard.gif',
                    types: ['fire', 'flying']
                )
            ),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'pikachu',
            'pokemon_two' => 'charizard',
        ]);

        $response->assertOk()
            ->assertJsonPath('pokemon_one.name', 'pikachu')
            ->assertJsonPath('pokemon_one.hp', 35)
            ->assertJsonPath('pokemon_one.sprite', 'https://example.com/pikachu.png')
            ->assertJsonPath('pokemon_one.animated_sprite', 'https://example.com/pikachu.gif')
            ->assertJsonPath('pokemon_one.types.0', 'electric')
            ->assertJsonPath('pokemon_two.name', 'charizard')
            ->assertJsonPath('pokemon_two.hp', 78)
            ->assertJsonPath('result.type', 'winner')
            ->assertJsonPath('result.winner', 'charizard');
    }

    public function test_it_returns_pokemon_one_as_winner_when_it_has_more_hp(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/snorlax' => Http::response(
                $this->pokemonResponse(
                    name: 'snorlax',
                    hp: 160,
                    sprite: 'https://example.com/snorlax.png',
                    animatedSprite: 'https://example.com/snorlax.gif',
                    types: ['normal']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pokemonResponse(
                    name: 'pikachu',
                    hp: 35,
                    sprite: 'https://example.com/pikachu.png',
                    animatedSprite: 'https://example.com/pikachu.gif',
                    types: ['electric']
                )
            ),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'snorlax',
            'pokemon_two' => 'pikachu',
        ]);

        $response->assertOk()
            ->assertJsonPath('pokemon_one.name', 'snorlax')
            ->assertJsonPath('pokemon_one.hp', 160)
            ->assertJsonPath('pokemon_two.name', 'pikachu')
            ->assertJsonPath('pokemon_two.hp', 35)
            ->assertJsonPath('result.type', 'winner')
            ->assertJsonPath('result.winner', 'snorlax');
    }

    public function test_it_returns_draw_when_both_pokemons_have_same_hp(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/ditto' => Http::response(
                $this->pokemonResponse(
                    name: 'ditto',
                    hp: 48,
                    sprite: 'https://example.com/ditto.png',
                    animatedSprite: 'https://example.com/ditto.gif',
                    types: ['normal']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/jigglypuff' => Http::response(
                $this->pokemonResponse(
                    name: 'jigglypuff',
                    hp: 48,
                    sprite: 'https://example.com/jigglypuff.png',
                    animatedSprite: 'https://example.com/jigglypuff.gif',
                    types: ['normal', 'fairy']
                )
            ),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'ditto',
            'pokemon_two' => 'jigglypuff',
        ]);

        $response->assertOk()
            ->assertJsonPath('pokemon_one.hp', 48)
            ->assertJsonPath('pokemon_two.hp', 48)
            ->assertJsonPath('result.type', 'draw')
            ->assertJsonPath('result.winner', null);
    }

    public function test_it_returns_error_when_a_pokemon_does_not_exist(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pokemonResponse(
                    name: 'pikachu',
                    hp: 35,
                    sprite: 'https://example.com/pikachu.png',
                    animatedSprite: 'https://example.com/pikachu.gif',
                    types: ['electric']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/naoexiste' => Http::response([], 404),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'pikachu',
            'pokemon_two' => 'naoexiste',
        ]);

        $response->assertStatus(404)
            ->assertJsonPath('message', "O Pokémon 'naoexiste' não foi encontrado.");
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/battles', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'pokemon_one',
                'pokemon_two',
            ]);
    }

    public function test_it_normalizes_pokemon_names_before_calling_pokeapi(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pokemonResponse(
                    name: 'pikachu',
                    hp: 35,
                    sprite: 'https://example.com/pikachu.png',
                    animatedSprite: 'https://example.com/pikachu.gif',
                    types: ['electric']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/charizard' => Http::response(
                $this->pokemonResponse(
                    name: 'charizard',
                    hp: 78,
                    sprite: 'https://example.com/charizard.png',
                    animatedSprite: 'https://example.com/charizard.gif',
                    types: ['fire', 'flying']
                )
            ),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => '  PiKaChu  ',
            'pokemon_two' => ' CHARIZARD ',
        ]);

        $response->assertOk()
            ->assertJsonPath('pokemon_one.name', 'pikachu')
            ->assertJsonPath('pokemon_two.name', 'charizard')
            ->assertJsonPath('result.winner', 'charizard');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://pokeapi.co/api/v2/pokemon/pikachu';
        });

        Http::assertSent(function ($request) {
            return $request->url() === 'https://pokeapi.co/api/v2/pokemon/charizard';
        });
    }

    private function pokemonResponse(
        string $name,
        int $hp,
        string $sprite,
        string $animatedSprite,
        array $types
    ): array {
        return [
            'name' => $name,
            'stats' => [
                [
                    'base_stat' => $hp,
                    'effort' => 0,
                    'stat' => [
                        'name' => 'hp',
                        'url' => 'https://pokeapi.co/api/v2/stat/1/',
                    ],
                ],
            ],
            'sprites' => [
                'front_default' => $sprite,
                'other' => [
                    'showdown' => [
                        'front_default' => $animatedSprite,
                    ],
                    'official-artwork' => [
                        'front_default' => $sprite,
                    ],
                ],
                'versions' => [
                    'generation-v' => [
                        'black-white' => [
                            'animated' => [
                                'front_default' => $animatedSprite,
                            ],
                        ],
                    ],
                ],
            ],
            'types' => array_map(
                fn (string $type) => [
                    'type' => [
                        'name' => $type,
                    ],
                ],
                $types
            ),
        ];
    }
}
