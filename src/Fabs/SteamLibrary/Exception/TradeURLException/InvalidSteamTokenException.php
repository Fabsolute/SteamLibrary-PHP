<?php

namespace Fabs\SteamLibrary\Exception\TradeURLException;


class InvalidSteamTokenException extends TradeURLException
{
    /**
     * InvalidSteamTokenException constructor.
     * @param string $token
     */
    public function __construct($token)
    {
        parent::__construct('Invalid steam token ' . $token);
    }
}