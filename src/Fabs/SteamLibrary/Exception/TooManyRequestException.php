<?php

namespace Fabs\SteamLibrary\Exception;


class TooManyRequestException extends SteamLibraryException
{

    public function __construct($url)
    {
        parent::__construct('Too many request exception ' . $url);
    }
}