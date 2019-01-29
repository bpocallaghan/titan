<?php

namespace Bpocallaghan\Titan;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Bpocallaghan\Titan\Commands\SetupCommand;
use Bpocallaghan\Titan\Commands\InstallCommand;
use Bpocallaghan\Titan\Commands\PublishCommand;
use Bpocallaghan\Titan\Commands\DatabaseSeedCommand;

class TitanServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // register RouteServiceProvider
        $this->app->register('Bpocallaghan\Titan\Providers\RouteServiceProvider');
    }

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
        $viewsPath = $basePath . "resources" . DIRECTORY_SEPARATOR . "views";

        // merge config
        $configPath = $basePath . '/config/config.php';
        $this->mergeConfigFrom($configPath, 'titan');

        // load migrations
        $this->loadMigrationsFrom($migrationsPath);

        $this->loadViewsFrom($viewsPath, "titan");

        $this->registerCommand(SetupCommand::class, 'setup');
        $this->registerCommand(InstallCommand::class, 'install');
        $this->registerCommand(PublishCommand::class, 'publish');
        $this->registerCommand(DatabaseSeedCommand::class, 'db:seed');

        // register RouteServiceProvider
        //$this->app->register('Bpocallaghan\Titan\Providers\RouteServiceProvider');

        // register EventServiceProvider
        $this->app->register('Bpocallaghan\Titan\Providers\EventServiceProvider');

        // register HelperServiceProvider
        $this->app->register('Bpocallaghan\Titan\Providers\HelperServiceProvider');
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
}
