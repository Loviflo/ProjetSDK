<?php

namespace App\Controller;

class IndexController
{
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

        $queryParams = http_build_query(array(
            "client_id" => "418310210187577",
            "redirect_uri" => "http://localhost:8081/fb_callback",
            "response_type" => "code",
            "scope" => "public_profile,email",
            "state" => bin2hex(random_bytes(16))
        ));
        echo "<a href=\"https://www.facebook.com/v2.10/dialog/oauth?{$queryParams}\">Se connecter via Facebook</a><br/>";

        $queryParams = http_build_query(array(
            "client_id" => "163659216436-lcj7h65gqut854e0oai5ktjn7bbbefk3.apps.googleusercontent.com",
            "redirect_uri" => "http://localhost:8081/google_callback",
            "response_type" => "code",
            "scope" => "profile email openid",
            "state" => bin2hex(random_bytes(16))
        ));

        echo "<a href=\"https://accounts.google.com/o/oauth2/auth/oauthchooseaccount?{$queryParams}\">Se connecter via Google</a><br/>";
        $queryParams = http_build_query(
            [
                "client_id" => "e52e6e751ff54609c25e",
                "redirect_uri" => "http://localhost:8081/gh_callback",
                "response_type" => "code",
                "scope" => "read:user,user:email",
                "state" => bin2hex(random_bytes(16))
            ]
        );
        echo "<a href=\"https://github.com/login/oauth/authorize?{$queryParams}\">Se connecter via Github</a><br/>";
    }
}
