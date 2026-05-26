<?php

namespace App\Mappers;

use App\DTOs\PokemonDTO;
use App\Exceptions\InvalidPokeApiResponseException;

class PokemonMapper
{
    public function mapToDTO(mixed $data, string $searchedName): PokemonDTO
    {
        if (!is_array($data)) {
            throw new InvalidPokeApiResponseException($searchedName);
        }

        return new PokemonDTO(
            name: $this->extractName($data, $searchedName),
            hp: $this->extractHp($data, $searchedName),
            sprite: $this->extractSprite($data),
            animatedSprite: $this->extractAnimatedSprite($data),
            types: $this->extractTypes($data),
        );
    }

    private function extractName(array $data, string $searchedName): string
    {
        if (empty($data['name']) || !is_string($data['name'])) {
            throw new InvalidPokeApiResponseException($searchedName);
        }

        return $data['name'];
    }

    private function extractHp(array $data, string $pokemonName): int
    {
        $hpStat = collect($data['stats'] ?? [])
            ->firstWhere('stat.name', 'hp');

        if (!$hpStat || !isset($hpStat['base_stat']) || !is_numeric($hpStat['base_stat'])) {
            throw new InvalidPokeApiResponseException($pokemonName);
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
            ->filter()
            ->values()
            ->all();
    }
}
