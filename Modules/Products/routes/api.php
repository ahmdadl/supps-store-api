<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
 */

Route::middleware(["auth:guest,customer"])->group(function () {
    Route::apiResource("products", ProductsController::class)
        ->parameter("products", "activeProduct")
        ->only(["index", "show"]);
});
