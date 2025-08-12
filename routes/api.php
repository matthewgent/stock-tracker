<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'member',
], function () {
    Route::get('tickers', [Api\TickerController::class, 'search']);
});
