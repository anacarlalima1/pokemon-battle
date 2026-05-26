<?php

namespace App\Http\Controllers;

use App\Http\Requests\BattleRequest;
use App\Services\BattleService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use RuntimeException;

class BattleController extends Controller
{
    public function __invoke(BattleRequest $request, BattleService $battleService): JsonResponse
    {
        try {
            $battle = $battleService->battle(
                $request->string('pokemon_one')->toString(),
                $request->string('pokemon_two')->toString(),
            );

            return response()->json($battle);
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }
    }
}
