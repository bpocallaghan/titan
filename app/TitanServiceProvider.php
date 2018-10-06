<?php

namespace Bpocallaghan\Titan;

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
}
