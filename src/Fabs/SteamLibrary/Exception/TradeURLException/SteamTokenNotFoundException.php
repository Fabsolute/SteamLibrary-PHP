<?php

namespace Fabs\SteamLibrary\Exception\TradeURLException;


class SteamTokenNotFoundException extends TradeURLException
{
    /**
     * SteamTokenNotFoundException constructor.
     * @param string $trade_url
     */
    public function __construct($trade_url)
    {
        parent::__construct('Invalid or missing token in ' . $trade_url);
    }
}