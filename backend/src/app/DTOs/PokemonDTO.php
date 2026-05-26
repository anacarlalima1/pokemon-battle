<?php

namespace App\DTOs;

class PokemonDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $hp,
        public readonly ?string $sprite,
        public readonly ?string $animatedSprite,
        public readonly array $types = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'hp' => $this->hp,
            'sprite' => $this->sprite,
            'animated_sprite' => $this->animatedSprite,
            'types' => $this->types,
        ];
    }
}
