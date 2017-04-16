<?php

namespace Fabs\SteamLibrary\Exception;

class SteamLibraryException extends \Exception
{
    /**
     * SteamLibraryException constructor.
     * @param string $reason
     */
    public function __construct($reason)
    {
        parent::__construct($reason);
    }
}