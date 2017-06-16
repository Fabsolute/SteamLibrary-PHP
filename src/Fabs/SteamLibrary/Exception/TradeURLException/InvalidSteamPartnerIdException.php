<?php


namespace Fabs\SteamLibrary\Exception\TradeURLException;


class InvalidSteamPartnerIdException extends TradeURLException
{
    /**
     * InvalidSteamPartnerIdException constructor.
     * @param string $partner_id
     */
    public function __construct($partner_id)
    {
        parent::__construct('Invalid steam partner id ' . $partner_id);
    }
}