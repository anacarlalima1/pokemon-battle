<?php

namespace App\Integrations;

use App\Exceptions\PokeApiUnavailableException;
use App\Exceptions\PokemonNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class PokeApi
{
    public function getPokemonByName(string $name): array
    {
        try {
            return Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->timeout(10)
                ->retry(2, 200)
                ->get("/pokemon/{$name}")
                ->throw()
                ->json();
        } catch (ConnectionException) {
            throw new PokeApiUnavailableException();
        } catch (RequestException $exception) {
            if ($exception->response?->status() === Response::HTTP_NOT_FOUND) {
                throw new PokemonNotFoundException($name);
            }

            throw new PokeApiUnavailableException();
        }
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.pokeapi.url'), '/');
    }
}
