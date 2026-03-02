<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::post('/webhooks/{source}', [ EventController::class, 'handler' ]);
Route::post('/example', function () {
    return response()->json(['message' => 'API route is working']);
});