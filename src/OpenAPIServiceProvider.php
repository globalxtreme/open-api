<?php

namespace GlobalXtreme\OpenAPI;

use Illuminate\Support\ServiceProvider;

class OpenAPIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/open-api.php' => config_path('open-api.php'),
        ],'open-api-config');
    }

    public function register()
    {
        $this->commands(
            \GlobalXtreme\OpenAPI\Command\OpenAPICredentialCommand::class
        );
    }
}
