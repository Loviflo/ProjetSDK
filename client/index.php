<?php

namespace App;

use App\Controller\ErrorController;
use App\Controller\IndexController;
use App\Facade\ProviderFacade;
use App\Provider\FacebookProvider;
use App\Provider\GithubProvider;
use App\Provider\GoogleProvider;
use App\Provider\OwnProvider;

function myAutoloader($class)
{
    $class = str_ireplace("App\\", "", $class);
    $class = str_replace("\\", "/", $class);
    if (file_exists($class . ".class.php")) {
        include $class . ".class.php";
    } elseif (file_exists($class . ".php")) {
        include $class . ".php";
    }
}

spl_autoload_register("App\myAutoloader");

include 'Utilities/Helpers.php';

$route = $_SERVER['REQUEST_URI'];
switch (strtok($route, "?")) {
    case '/login':
        (new IndexController())->login();
        break;
    case '/callback':
        (new ProviderFacade('own'));
        break;
    case '/fb_callback':
        (new ProviderFacade('facebook'));
        break;
    case '/google_callback':
        (new ProviderFacade('google'));
        break;
    case '/gh_callback':
        (new ProviderFacade('github'));
        break;
    case '/discord_callback':
        (new ProviderFacade('discord'));
        break;
    default:
        (new ErrorController)->Error404();
}
