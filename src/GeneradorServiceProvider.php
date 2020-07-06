<?php

namespace TesGen\Generador;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use TesGen\Generador\Utils\ArchivoUtil;

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

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('roleAuth', \App\Http\Middleware\RolesAuth::class);
        $router->pushMiddlewareToGroup('roleAuthApi', \App\Http\Middleware\RolesAuthApi::class);
        $router->pushMiddlewareToGroup('webAuth', \App\Http\Middleware\WebAuth::class);

        $directorio_pantilla_auth = base_path('vendor\\tesgen\\generador\\plantillas\\config\\auth.txt');
        $contenidoPlantillaAuth = file_get_contents($directorio_pantilla_auth);
        ArchivoUtil::createFile(config_path(), "auth.php", $contenidoPlantillaAuth);

        $directorio_pantilla_provider = base_path('vendor\\tesgen\\generador\\plantillas\\providers\\AuthServiceProvider.txt');
        $contenidoPlantillaProvider = file_get_contents($directorio_pantilla_provider);
        ArchivoUtil::createFile(app_path('Providers'), "AuthServiceProvider.php", $contenidoPlantillaProvider);

        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);
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
            __DIR__ . '/../archivos/app/Http' => app_path('Http'),
//            __DIR__ . '/../archivos/app/Http/Middleware' => app_path('Http/Middleware'),
            __DIR__ . '/../archivos/config/jsvalidation.php' => config_path('jsvalidation.php'),
            __DIR__ . '/../archivos/public' => public_path(),
            __DIR__ . '/../archivos/resources' => resource_path(),
        ], 'generador.archivos');

        //asdfasdfasdasdfasdf

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
