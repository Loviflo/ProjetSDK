<?php

class AuthProvider
{
    protected $fb;
    protected $google;
    protected $github;

    function __construct($fb,$google,$github)
    {
        $this->fb = $fb;
        $this->google  = $google;
        $this->github  = $github;
    }

    function public login()
}