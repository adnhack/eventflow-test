<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EnrichmentController;

Route::post('/webhooks/{source}', [ EventController::class, 'handler' ]);
Route::post('/enriched/data', [ EnrichmentController::class, 'getData' ]);