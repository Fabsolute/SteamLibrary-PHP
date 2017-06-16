<?php

namespace Fabs\SteamLibrary\Exception\TradeURLException;


class NotASteamTradeURLException extends TradeURLException
{
    /**
     * InvalidTradeURLException constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct('Not a steam trade url ' . $url);
    }
}