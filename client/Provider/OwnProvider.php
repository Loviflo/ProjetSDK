<?php

namespace App\Provider;

use App\Interface\ProviderInterface;

class OwnProvider implements ProviderInterface
{
    function callback(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $specifParams = [
                "grant_type" => "password",
                "username" => $_POST["username"],
                "password" => $_POST["password"]
            ];
        } else {
            $specifParams = [
                "grant_type" => "authorization_code",
                "code" => $_GET["code"],
            ];
        }
        $clientId = "621e3b8d1f964";
        $clientSecret = "621e3b8d1f966";
        $redirectUri = "http://localhost:8081/callback";
        $data = http_build_query(array_merge([
            "redirect_uri" => $redirectUri,
            "client_id" => $clientId,
            "client_secret" => $clientSecret
        ], $specifParams));
        $url = "http://oauth-server:8080/token?{$data}";
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $accessToken = $result['access_token'];

        $url = "http://oauth-server:8080/me";
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $accessToken
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);
        echo "Hello {$result['lastname']}";
    }
}
