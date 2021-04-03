<?php

use App\Http\Controllers\CommentAdminController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\NovelChapterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReadingListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::feeds();


Route::prefix('backend')->name('backend.')->middleware(['auth:web', 'can:access backend'])->group(function () {
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');

    Route::resource('series', SeriesController::class)->except(['show']);
    Route::post('/series/bulkUpdate', [SeriesController::class, 'bulkUpdate'])->name('series.bulkUpdate');
    Route::resource('novelChapter', NovelChapterController::class)->except(['show']);
    Route::post('/novelChapter/bulkUpdate', [NovelChapterController::class, 'bulkUpdate'])->name('novelChapter.bulkUpdate');

    Route::resource('comment', CommentAdminController::class)->except([
        'show', 'create', 'store', 'destroy'
    ]);
    Route::post('/comment/bulkUpdate', [CommentAdminController::class, 'bulkUpdate'])->name('comment.bulkUpdate');

    // User
    Route::resource('user', UserController::class);
    // User Roles & Permissions
    Route::get('/getPermissions', [UserRoleController::class, 'getPermissions'])->name('userRole.getPermissions');
    Route::patch('/updatePermission', [UserRoleController::class, 'updatePermission'])->name('userRole.updatePermission');
    Route::apiResource('userRole', UserRoleController::class)->except(['show']);
    // Make new role and permission
    Route::post('/role', [UserRoleController::class, 'role_store'])->name('role.store');
    Route::delete('/role', [UserRoleController::class, 'role_destroy'])->name('role.destroy');
    Route::post('/permission', [UserRoleController::class, 'permission_store'])->name('permission.store');
    Route::delete('/permission', [UserRoleController::class, 'permission_destroy'])->name('permission.destroy');

    Route::resource('tag', TagController::class)->only(['index','edit','update','destroy']);
    Route::post('/tag/bulkUpdate', [TagController::class, 'bulkUpdate'])->name('tag.bulkUpdate');
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'update'])->name('update');
    });

});


Route::get('/', [PageController::class, 'home'])->name('home');

Route::name('frontend.')->group(function () {
    Route::prefix('read')->group(function () {
        Route::get('/', [SeriesController::class, 'show'])->name('series');
        Route::get('/{slug}', [SeriesController::class, 'show'])->name('series.show');
        Route::get('/{novel_slug}/{chapter_num}', [NovelChapterController::class, 'show'])->name('novelChapter.show');
    });

    Route::get('/novels', [SeriesController::class, 'archive'])->name('novels.archive');

    Route::resource('tag', TagController::class)->except(['index','create','store']);
    
    Route::prefix('page')->name('page.')->group(function () {
        Route::get('/{page_slug}', [PageController::class, 'page'])->name('default');
    });

    Route::resource('reading_list', ReadingListController::class)->only(['index','store','destroy']);

    Route::resource('notification', NotificationController::class)->only(['index','update','destroy']);
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'edit_self_front'])->name('edit');
        Route::patch('/', [UserController::class, 'update_self_front'])->name('update');
    });
});
