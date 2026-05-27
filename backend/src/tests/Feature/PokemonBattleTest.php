<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
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
            ->assertJsonPath('pokemon_two.types.0', 'fire')
            ->assertJsonPath('pokemon_two.types.1', 'flying')
            ->assertJsonPath('result.type', 'winner')
            ->assertJsonPath('result.winner', 'pokemon_two')
            ->assertJsonPath('result.winner_name', 'charizard');
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
            ->assertJsonPath('result.winner', 'pokemon_one')
            ->assertJsonPath('result.winner_name', 'snorlax');
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
            ->assertJsonPath('result.winner', null)
            ->assertJsonPath('result.winner_name', null);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/battles', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
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
            ->assertJsonPath('result.winner', 'pokemon_two')
            ->assertJsonPath('result.winner_name', 'charizard');

        Http::assertSent(fn ($request) => $request->url() === 'https://pokeapi.co/api/v2/pokemon/pikachu');

        Http::assertSent(fn ($request) => $request->url() === 'https://pokeapi.co/api/v2/pokemon/charizard');
    }

    public function test_it_returns_404_when_pokemon_does_not_exist(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pokemonResponse(
                    name: 'pikachu',
                    hp: 35,
                    sprite: 'pikachu.png',
                    animatedSprite: 'pikachu.gif',
                    types: ['electric']
                )
            ),

            'https://pokeapi.co/api/v2/pokemon/invalid' => Http::response([], Response::HTTP_NOT_FOUND),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'pikachu',
            'pokemon_two' => 'invalid',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', "Pokémon invalid não encontrado.");
    }

    public function test_it_returns_503_when_pokeapi_is_unavailable(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/*' => Http::failedConnection(),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'pikachu',
            'pokemon_two' => 'charizard',
        ]);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->assertJsonPath('message', 'A PokeAPI está indisponível no momento.');
    }

    public function test_it_returns_502_when_pokeapi_response_has_no_hp(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
                'name' => 'pikachu',
                'stats' => [],
                'sprites' => [],
                'types' => [],
            ]),

            'https://pokeapi.co/api/v2/pokemon/charizard' => Http::response(
                $this->pokemonResponse(
                    name: 'charizard',
                    hp: 78,
                    sprite: 'charizard.png',
                    animatedSprite: 'charizard.gif',
                    types: ['fire']
                )
            ),
        ]);

        $response = $this->postJson('/api/battles', [
            'pokemon_one' => 'pikachu',
            'pokemon_two' => 'charizard',
        ]);

        $response->assertStatus(Response::HTTP_BAD_GATEWAY)
            ->assertJsonPath('message', "Resposta inválida da PokeAPI para o Pokémon pikachu.");
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
