<?php
namespace App\Interface;

interface ProviderInterface
{
    function callback(object $provider): void;
}