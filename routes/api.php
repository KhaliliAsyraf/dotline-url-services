<?php

use App\Http\Controllers\URLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'prefix' => 'url'
    ],
    function () {
        Route::post('/', [URLController::class, 'storeURL'])->middleware('throttle:create-shorten-url')->name('url.store');
        Route::get('/analytic-data', [URLController::class, 'getAnalyticData'])->name('url.analytic-data');
    }
);

Route::get('/{url}', [URLController::class, 'redirect'])->name('redirect');