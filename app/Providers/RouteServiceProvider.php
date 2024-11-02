<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function boot()
    {
        //parent::boot();
        /*$this->routes(function () {
            foreach (Finder::create()->in(base_path('routes/api'))->files() as $file) {
                Route::middleware('api')
                    ->prefix(trim($file->getBasename(), '.php'))
                    ->namespace($this->namespace)
                    ->group($file->getRealPath());
            }
        });*/
    }

    public function map()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1.php'));

        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v2.php'));
    }
}
