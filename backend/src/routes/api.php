<?php

use App\Http\Controllers\BattleController;
use Illuminate\Support\Facades\Route;

Route::post('/battles', BattleController::class);

Route::get('/health', function () {
    return response()->json([
        'message' => 'API funcionando',
    ]);
});
