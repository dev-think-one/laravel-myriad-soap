<?php

namespace MyriadSoap;

use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/myriad-soap.php' => config_path('myriad-soap.php'),
            ], 'config');


            $this->commands([]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/myriad-soap.php', 'myriad-soap');

        $this->app->singleton('myriad_soap', function ($app) {
            return new MyriadApi(
                new MyriadSoapClient(null, $app['config']['myriad-soap']['options'])
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'myriad_soap' ];
    }
}
