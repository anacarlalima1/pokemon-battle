<?php

namespace App\Http\Resources;

use App\DTOs\BattleDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BattleDTO
 */
class BattleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'pokemon_one' => new PokemonResource($this->pokemonOne),
            'pokemon_two' => new PokemonResource($this->pokemonTwo),
            'result' => new BattleResultResource($this->result),
        ];
    }
}
