<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PokeApiUnavailableException extends Exception
{
    public function __construct()
    {
        parent::__construct('A PokeAPI está indisponível no momento.');
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
