<?php

namespace HairyLemonLtd\Deployer;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeployerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected $commands = [
        Commands\DeployInit::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'deployer');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'deployer');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            // Registering package commands.
            $this->commands($this->commands);

            // boiler plate
            /*$this->publishes([
                __DIR__.'/../config/config.php' => config_path('deployer.php'),
            ], 'config');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/deployer'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/deployer'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/deployer'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
       /* // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'deployer');*/

        // Register the main class to use with the facade
        /*$this->app->singleton('deployer', function () {
            return new Deployer;
        });*/
    }

    public function provides(): array
    {
        return [
            'command.deploy.init'
        ];
    }
}
