<?php

namespace IRobot\Core\Exception\TradeURLException;


class SteamPartnerIdNotFoundException extends TradeURLException
{
    /**
     * SteamPartnerIdNotFoundException constructor.
     * @param string $trade_url
     */
    public function __construct($trade_url)
    {
        parent::__construct('Invalid or missing partner id in ' . $trade_url);
    }
}