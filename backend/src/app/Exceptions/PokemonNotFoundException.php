<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PokemonNotFoundException extends Exception
{
    public function __construct(string $pokemonName)
    {
        parent::__construct("Pokémon {$pokemonName} não encontrado.");
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], Response::HTTP_NOT_FOUND);
    }
}
