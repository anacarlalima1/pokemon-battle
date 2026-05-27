<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BattleResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'winner' => $this->winner,
            'winner_name' => $this->winnerName,
            'message' => $this->message,
        ];
    }
}
