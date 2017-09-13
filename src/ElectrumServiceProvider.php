<?php

namespace AraneaDev\Electrum;

use Illuminate\Support\ServiceProvider;
use AraneaDev\Electrum\App\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use AraneaDev\Electrum\App\Console\ElectrumCommand;

/**
 * Class ElectrumServiceProvider.
 */
class ElectrumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Bind the package routes
        include __DIR__.'/routes/electrum.php';

        // Bind the console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ElectrumCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package configuration
        $this->mergeConfigFrom(
            __DIR__.'/config/electrum.php', 'electrum'
        );

        // Load the package views
        $this->loadViewsFrom(__DIR__.'/views', 'electrum');

        // Make the package controller
        $this->app->make('AraneaDev\Electrum\App\Http\Controllers\IndexController');

        // Make the package's custom task scheduling kernel
        $this->app->singleton('araneadev.electrum.app.console.kernel', function ($app) {
            $dispatcher = $app->make(Dispatcher::class);

            return new Kernel($app, $dispatcher);
        });
        $this->app->make('araneadev.electrum.app.console.kernel');

        // Publish the package assets
        $this->publish_assets();
    }

    /**
     * Publish the package assets.
     *
     * @return void
     */
    public function publish_assets()
    {
        $this->publishes([
            __DIR__.'/views'                            => resource_path('views/vendor/electrum'),
            __DIR__.'/resources/assets/js/Electrum.vue' => resource_path('assets/js/vendor/araneadev/Electrum.vue'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/config/electrum.php' => config_path('electrum.php'),
        ], 'config');
    }
}
