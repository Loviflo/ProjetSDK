<?php

namespace App\Provider;

use App\Interface\ProviderInterface;

class GithubProvider implements ProviderInterface
{
    function callback(object $provider): void
    {
        $specifParams = [
            'grant_type' => 'authorization_code',
            'code' => $_GET['code'],
        ];
        $clientId = $provider->client_id;
        $clientSecret = $provider->client_secret;
        $redirectUri = $provider->redirect_uri;
        $data = http_build_query(
            array_merge(
                [
                    "redirect_uri" => $redirectUri,
                    "client_id" => $clientId,
                    "client_secret" => $clientSecret
                ],
                $specifParams
            )
        );

        $url = $provider->access_token_url;
        $options = [
            'http' => [
                'header' => [
                    "Content-Type: application/x-www-form-urlencoded",
                    "Accept: application/json"
                ],
                'method' => 'POST',
                'content' => $data
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);

        $accessToken = $result['access_token'];
        $url = $provider->user_info_url;
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1',
                    'Authorization: token ' . $accessToken
                ]
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);
        echo "Hello {$result['login']}";
    }
}
