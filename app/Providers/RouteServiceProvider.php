<?php

namespace Bpocallaghan\Titan\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Bpocallaghan\Titan\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $appPath = __DIR__ . DIRECTORY_SEPARATOR;
        $path = $appPath . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "routes" . DIRECTORY_SEPARATOR;

        $this->mapApiRoutes($path);

        $this->mapWebRoutes($path);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes($path)
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group($path . ('web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes($path)
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group($path . ('api.php'));
    }
}
