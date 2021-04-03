<?php

namespace App\Providers;

use App\Observers\CommentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravelista\Comments\Comment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Laravel\Passport\Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
        Comment::observe(CommentObserver::class);
    }
}
