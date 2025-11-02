<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelexWebhookController;

Route::post('/telex/webhook', [TelexWebhookController::class, 'handle']);

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});