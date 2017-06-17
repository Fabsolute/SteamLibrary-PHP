<?php

namespace Fabs\SteamLibrary\Exception\TradeURLException;

use Fabs\SteamLibrary\Exception\SteamLibraryException;

class TradeURLException extends SteamLibraryException
{
    /**
     * TradeURLException constructor.
     * @param string $reason
     */
    public function __construct($reason)
    {
        parent::__construct($reason);
    }
}