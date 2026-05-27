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

        $name = data_get($data, 'name');
        $hp = $this->extractHp($data, $searchedName);

        if (!is_string($name) || blank($name)) {
            throw new InvalidPokeApiResponseException($searchedName);
        }

        return new PokemonDTO(
            name: $name,
            hp: $hp,
            sprite: $this->firstValidString([
                data_get($data, 'sprites.front_default'),
                data_get($data, 'sprites.other.official-artwork.front_default'),
            ]),
            animatedSprite: $this->firstValidString([
                data_get($data, 'sprites.other.showdown.front_default'),
                data_get($data, 'sprites.versions.generation-v.black-white.animated.front_default'),
                data_get($data, 'sprites.front_default'),
            ]),
            types: $this->extractTypes($data),
        );
    }

    private function extractHp(array $data, string $searchedName): int
    {
        $hp = collect(data_get($data, 'stats', []))
            ->firstWhere('stat.name', 'hp');

        $baseStat = data_get($hp, 'base_stat');

        if (!is_numeric($baseStat)) {
            throw new InvalidPokeApiResponseException($searchedName);
        }

        return (int) $baseStat;
    }

    private function extractTypes(array $data): array
    {
        return collect(data_get($data, 'types', []))
            ->pluck('type.name')
            ->filter(fn ($type) => is_string($type) && filled($type))
            ->values()
            ->all();
    }

    private function firstValidString(array $values): ?string
    {
        return collect($values)
            ->first(fn ($value) => is_string($value) && filled($value));
    }
}
