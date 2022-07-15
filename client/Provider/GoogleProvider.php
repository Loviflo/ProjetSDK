<?php

namespace App\Provider;

use App\Interface\ProviderInterface;

class GoogleProvider implements ProviderInterface
{
    function callback(): void
    {
    
        $specifParams = [
                "grant_type" => "authorization_code",
                "code" => $_GET["code"],
            ];
        $clientId = "163659216436-lcj7h65gqut854e0oai5ktjn7bbbefk3.apps.googleusercontent.com";
        $clientSecret = "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";
        $redirectUri = "http://localhost:8081/google_callback";
        $data = http_build_query(array_merge([
            "redirect_uri" => $redirectUri,
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
        ], $specifParams));
    
        $url = "https://oauth2.googleapis.com/token";
        $options = array(
            'http' => array(
                'header' => [
                    "Content-Type: application/x-www-form-urlencoded",
                    "Accept: application/json"
                ],
                'method' => 'POST',
                'content' => $data
    
            )
        );
    
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);
        $accessToken = $result['access_token'];
        $url = "https://openidconnect.googleapis.com/v1/userinfo";
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1',
                    'Authorization: Bearer ' . $accessToken
                ]
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    
    
        $result = json_decode($result, true);
        echo "Hello {$result['name']}";
    
    }
}
