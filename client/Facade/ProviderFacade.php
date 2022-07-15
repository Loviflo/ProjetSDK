<?php

namespace App\Facade;

use Exception;

class ProviderFacade
{
    private $providers = [];

    function __construct(string $provider)
    {
        $this->getProviders();
        $this->callProvider($provider);
    }

    function getProviders(): void
    {
        $providersJson = file_get_contents('providers.json');
        $providers = json_decode($providersJson)->providers;
        foreach($providers as $providerName => $provider) {
            $this->providers[] = $providerName;
        }
    }

    function callProvider(string $provider): void
    {
        if(!in_array(strtolower($provider), $this->providers)) {
            throw new Exception('Ce provider n\'existe pas');
        }
        $providerClass = "\\App\\Provider\\" . ucfirst($provider) . "Provider";
        (new $providerClass())->callback();
    }
}