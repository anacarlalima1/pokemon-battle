<?php

namespace App\Http\Resources;

use App\DTOs\PokemonDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PokemonDTO
 */
class PokemonResource extends JsonResource
{
    public function toArray(Request $request): array
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
