<?php

namespace App\Services;

use App\DTOs\PokemonDTO;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class PokemonService
{
    public function findByName(string $name): PokemonDTO
    {
        $normalizedName = $this->normalizeName($name);

        try {
            $response = Http::timeout(10)
                ->retry(2, 200)
                ->get($this->baseUrl() . "/pokemon/{$normalizedName}")
                ->throw();
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 404) {
                throw new InvalidArgumentException("O Pokémon '{$name}' não foi encontrado.");
            }

            throw new RuntimeException('Não foi possível consultar a PokéAPI no momento.');
        }

        $data = $response->json();

        return new PokemonDTO(
            name: $data['name'],
            hp: $this->extractHp($data, $name),
            sprite: $this->extractSprite($data),
            animatedSprite: $this->extractAnimatedSprite($data),
            types: $this->extractTypes($data),
        );
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

    private function extractHp(array $data, string $name): int
    {
        $hpStat = collect($data['stats'] ?? [])
            ->firstWhere('stat.name', 'hp');

        if (!$hpStat || !isset($hpStat['base_stat'])) {
            throw new RuntimeException("Não foi possível encontrar o HP do Pokémon '{$name}'.");
        }

        return (int) $hpStat['base_stat'];
    }

    private function extractSprite(array $data): ?string
    {
        return $data['sprites']['front_default']
            ?? $data['sprites']['other']['official-artwork']['front_default']
            ?? null;
    }

    private function extractAnimatedSprite(array $data): ?string
    {
        return $data['sprites']['other']['showdown']['front_default']
            ?? $data['sprites']['versions']['generation-v']['black-white']['animated']['front_default']
            ?? $data['sprites']['front_default']
            ?? null;
    }

    private function extractTypes(array $data): array
    {
        return collect($data['types'] ?? [])
            ->pluck('type.name')
            ->values()
            ->all();
    }
}
