<?php

namespace App\Facade;

use Exception;

class ProviderFacade
{
    function __construct(string $provider)
    {
        $this->callProvider($provider);
    }

    function callProvider(string $provider): void
    {
        $providersJson = file_get_contents('providers.json');
        $providers = json_decode($providersJson)->providers;

        $provider = strtolower($provider);

        if(!property_exists($providers, $provider)) {
            throw new Exception('Ce provider n\'existe pas');
        }

        $providerClass = "\\App\\Provider\\" . ucfirst($provider) . "Provider";
        (new $providerClass())->callback($providers->$provider);
    }
}