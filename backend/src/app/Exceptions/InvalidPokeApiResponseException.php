<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidPokeApiResponseException extends Exception
{
    public function __construct(string $pokemonName)
    {
        parent::__construct("Resposta inválida da PokeAPI para o Pokémon {$pokemonName}.");
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], Response::HTTP_BAD_GATEWAY);
    }
}
