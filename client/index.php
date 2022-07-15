<?php

function login()
{
    $queryParams = http_build_query(array(
        "client_id" => "621e3b8d1f964",
        "redirect_uri" => "http://localhost:8081/callback",
        "response_type" => "code",
        "scope" => "read,write",
        "state" => bin2hex(random_bytes(16))
    ));
    echo "
        <form action='callback' method='POST'>
            <input type='text' name='username'>
            <input type='text' name='password'>
            <input type='submit' value='Login'>
        </form>
    ";
    echo "<a href=\"http://localhost:8080/auth?{$queryParams}\">Se connecter via Oauth Server</a><br/>";

    $queryParams = http_build_query(
        [
            "client_id" => "418310210187577",
            "redirect_uri" => "http://localhost:8081/fb_callback",
            "response_type" => "code",
            "scope" => "public_profile,email",
            "state" => bin2hex(random_bytes(16))
        ]
    );
    echo "<a href=\"https://www.facebook.com/v2.10/dialog/oauth?{$queryParams}\">Se connecter via Facebook</a><br/>";

    $queryParams = http_build_query(
        [
            "client_id" => "e52e6e751ff54609c25e",
            "redirect_uri" => "http://localhost:8081/gh_callback",
            "response_type" => "code",
            "scope" => "read:user,user:email",
            "state" => bin2hex(random_bytes(16))
        ]
    );
    echo "<a href=\"https://github.com/login/oauth/authorize?{$queryParams}\">Se connecter via Github</a>";
}

function callback()
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

function fbcallback()
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

function ghcallback()
{
    $specifParams = [
        'grant_type' => 'authorization_code',
        'code' => $_GET['code'],
    ];
    $clientId = 'e52e6e751ff54609c25e';
    $clientSecret = '8e87980eef1886b3f983b1df153232b2fda75b2e';
    $redirectUri = "http://localhost:8081/gh_callback";
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

    $url = "https://github.com/login/oauth/access_token";
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
    $url = "https://api.github.com/user";
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

$route = $_SERVER['REQUEST_URI'];
switch (strtok($route, "?")) {
    case '/login':
        login();
        break;
    case '/callback':
        callback();
        break;
    case '/fb_callback':
        fbcallback();
        break;
    case '/gh_callback':
        ghcallback();
        break;
    default:
        echo '404';
        break;
}
