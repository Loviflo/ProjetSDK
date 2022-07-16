<?php

namespace App\Controller;

class IndexController
{
    function login()
    {
        echo "
        <form action='callback' method='POST'>
            <input type='text' name='username'>
            <input type='text' name='password'>
            <input type='submit' value='Login'>
        </form>
        ";

        $providersJson = file_get_contents('providers.json');
        $providers = json_decode($providersJson)->providers;

        foreach ($providers as $provider) {
            $queryParams = http_build_query(array(
                "client_id" => $provider->client_id,
                "redirect_uri" => $provider->redirect_uri,
                "response_type" => $provider->response_type,
                "scope" => $provider->scope,
                "state" => bin2hex(random_bytes(16))
            ));
            echo "<a href=\"{$provider->auth_url}?{$queryParams}\">Se connecter via {$provider->name}</a><br/>";
        }
    }
}
