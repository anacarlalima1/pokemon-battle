<?php

namespace App\Http\Controllers;

use App\Http\Requests\BattleRequest;
use App\Http\Resources\BattleResource;
use App\Services\BattleService;

class BattleController extends Controller
{
    public function __invoke(BattleRequest $request, BattleService $battleService): BattleResource
    {
        return new BattleResource(
            $battleService->execute(
                $request->pokemonOne(),
                $request->pokemonTwo(),
            )
        );
    }
}
