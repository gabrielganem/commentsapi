<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(CommentController::class)->group(function () {
    Route::prefix('comments')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store');
        Route::patch('/{id}', 'update');
        Route::post('/{id}/reply', 'reply');
    });
});