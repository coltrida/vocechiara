<?php

namespace App\Providers;

use App\Models\Filiale;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function (View $view){
            $filiali = Filiale::orderBy('nome')->get();
            $view->with('filiali', $filiali);
        });

    }
}
