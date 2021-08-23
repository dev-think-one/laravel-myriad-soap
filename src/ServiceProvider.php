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
            $options = $app['config']['myriad-soap']['options'] ?? [];

            if (!empty($app['config']['myriad-soap']['use_http_version_1']) && !isset($options['stream_context'])) {
                $options['stream_context'] = stream_context_create(
                    [ 'http' => [ 'protocol_version' => 1.0 ] ]
                );
            }

            return new MyriadApi(
                new MyriadSoapClient(null, $options)
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
