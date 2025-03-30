<?php

namespace App\Providers;

use Framework\ServiceProvider;
use Framework\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutes();
    }

    /**
     * Load the routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $this->loadWebRoutes();
        $this->loadApiRoutes();
    }

    /**
     * Load the web routes.
     *
     * @return void
     */
    protected function loadWebRoutes()
    {
        require_once base_path('routes/web.php');
    }

    /**
     * Load the API routes.
     *
     * @return void
     */
    protected function loadApiRoutes()
    {
        Route::group(['prefix' => 'api'], function ($router) {
            require_once base_path('routes/api.php');
        });
    }
}
