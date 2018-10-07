<?php

namespace Bpocallaghan\Titan;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Bpocallaghan\Titan\Commands\PublishCommand;

class TitanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // mysql (Specified key was too long)
        Schema::defaultStringLength(191);

        $appPath = __DIR__ . DIRECTORY_SEPARATOR;
        $basePath = $appPath . ".." . DIRECTORY_SEPARATOR;
        $migrationsPath = $basePath . "database" . DIRECTORY_SEPARATOR . "migrations";

        // map routes
        $this->mapTitanRoutes();

        // load migrations
        $this->loadMigrationsFrom($migrationsPath);

        dump($appPath);
        dump($migrationsPath);
        //$this->loadViewsFrom($path, $namespace)

        $this->registerCommand(PublishCommand::class, 'publish');

        //dump('TitanServiceProvider');
    }

    /**
     * Register a singleton command
     *
     * @param $class
     * @param $command
     */
    private function registerCommand($class, $command)
    {
        $path = 'bpocallaghan.commands.';
        $this->app->singleton($path . $command, function ($app) use ($class) {
            return $app[$class];
        });

        $this->commands($path . $command);
    }

    /**
     * Map the titan routes
     */
    private function mapTitanRoutes()
    {
        $namespace = "Bpocallaghan\\Titan\\Http\\Controllers";
        // live package
        $path = base_path("vendor/bpocallaghan/titan/routes/");
        // developing package
        $path = base_path("bpocallaghan/titan/routes/");

        Route::middleware('web')
            ->namespace($namespace)
            ->group($path . "web.php");

        Route::prefix('api')
            ->middleware('api')
            ->namespace($namespace)
            ->group($path . "api.php");
    }
}
