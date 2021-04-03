<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UserRoleController;

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

Route::name('api.')->middleware('auth:api')->group(function () {
    Route::prefix('backend')->name('backend.')->middleware('can:access backend')->group(function () {
        Route::patch('/series/{id}/updateSlug', [SeriesController::class, 'updateSlug'])->name('series.updateSlug');
    });
});


// no need for auth, this is from cg
Route::prefix('cg')->name('api.cg.')->group(function () {
    Route::post('/check', [SeriesController::class, 'cg_check'])->name('check');
    Route::post('/series', [SeriesController::class, 'cg_series_store'])->name('series.store');
    Route::post('/chapters', [SeriesController::class, 'cg_chapters_store'])->name('chapters.store');
});