<?php

namespace App\Services;

use App\DTOs\PokemonDTO;
use App\Exceptions\PokeApiUnavailableException;
use App\Exceptions\PokemonNotFoundException;
use App\Mappers\PokemonMapper;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class PokemonService
{
    public function __construct(
        private readonly PokemonMapper $pokemonMapper,
    ) {
    }

    public function findByName(string $name): PokemonDTO
    {
        $normalizedName = $this->normalizeName($name);
        $url = $this->baseUrl() . "/pokemon/{$normalizedName}";

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->retry(2, 200)
                ->get($url)
                ->throw();
        } catch (ConnectionException) {
            throw new PokeApiUnavailableException();
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 404) {
                throw new PokemonNotFoundException($name);
            }

            throw new PokeApiUnavailableException();
        }

        return $this->pokemonMapper->mapToDTO($response->json(), $name);
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.pokeapi.url'), '/');
    }

    private function normalizeName(string $name): string
    {
        return str($name)
            ->trim()
            ->lower()
            ->ascii()
            ->replace(' ', '-')
            ->toString();
    }
}
