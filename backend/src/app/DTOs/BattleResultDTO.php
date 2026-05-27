<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class BattleResultDTO implements Arrayable
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $winner,
        public readonly ?string $winnerName,
        public readonly string $message,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'winner' => $this->winner,
            'winner_name' => $this->winnerName,
            'message' => $this->message,
        ];
    }
}
