<?php

namespace TesGen\Generador;

use Illuminate\Support\ServiceProvider;

class GeneradorServiceProvider extends ServiceProvider {
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tesgen');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tesgen');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/generador.php', 'generador');

        // Register the service the package provides.
        $this->app->singleton('generador', function ($app) {
            return new Generador;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['generador'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole() {
        $this->commands([
            Generador::class
        ]);
        // Publishing the configuration file.
        $this->publishes([
//            __DIR__ . '/../config/generador.php' => config_path('generador.php'),
            __DIR__ . '/../archivos/app/Http/Controllers/HomeController.php' =>
                app_path('Http/Controllers/HomeController.php'),
            __DIR__ . '/../archivos/config/jsvalidation.php' => config_path('jsvalidation.php'),
            __DIR__ . '/../archivos/public' => public_path(),
            __DIR__ . '/../archivos/resources/views' => resource_path('views'),
        ], 'generador.archivos');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/tesgen'),
        ], 'generador.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/tesgen'),
        ], 'generador.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/tesgen'),
        ], 'generador.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
