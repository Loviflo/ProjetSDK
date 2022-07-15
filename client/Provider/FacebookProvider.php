<?php

namespace App\Provider;

use App\Interface\ProviderInterface;

class FacebookProvider implements ProviderInterface
{
    function callback(): void
    {
        $specifParams = [
            "grant_type" => "authorization_code",
            "code" => $_GET["code"],
        ];
        $clientId = "418310210187577";
        $clientSecret = "82fef5a7f59581e542463e68888bb9f1";
        $redirectUri = "http://localhost:8081/fb_callback";
        $data = http_build_query(array_merge([
            "redirect_uri" => $redirectUri,
            "client_id" => $clientId,
            "client_secret" => $clientSecret
        ], $specifParams));
        $url = "https://graph.facebook.com/v2.10/oauth/access_token?{$data}";
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $accessToken = $result['access_token'];

        $url = "https://graph.facebook.com/v2.10/me";
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $accessToken
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);
        echo "Hello {$result['name']}";
    }
}
