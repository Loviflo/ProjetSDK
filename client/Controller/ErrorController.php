<?php

namespace App\Controller;

class ErrorController
{
    function Error404()
    {
        http_response_code(404);
        echo '<h1>404</h1>';
    }
}