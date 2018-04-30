<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 15/04/2017
 * Time: 17:54
 */

namespace Fabs\SteamLibrary;


use Fabs\SteamLibrary\Exception\TradeURLException\InvalidSteamPartnerIdException;
use Fabs\SteamLibrary\Exception\TradeURLException\InvalidSteamTokenException;
use Fabs\SteamLibrary\Exception\TradeURLException\NotASteamTradeURLException;
use Fabs\SteamLibrary\Exception\TradeURLException\SteamPartnerIdNotFoundException;
use Fabs\SteamLibrary\Exception\TradeURLException\SteamTokenNotFoundException;

class SteamTradeURLHandler
{

    /** @var  string */
    private $full_url = null;
    /** @var  string */
    private $partner_id = null;
    /** @var  string */
    private $token = null;

    private $base_url = 'https://steamcommunity.com/tradeoffer';
    private $prefix = '/new/?';
    private $partner_id_regex_base = '[0-9]*';
    private $partner_id_regex;
    private $token_regex_base = '[a-zA-Z0-9_-]{8}';
    private $token_regex;


    function __construct()
    {
        $this->partner_id_regex = '/partner=(' . $this->partner_id_regex_base . ')/';
        $this->token_regex = '/token=(' . $this->token_regex_base . ')/';
    }


    /**
     * @param string $partner_id
     * @return $this
     */
    public function setPartnerId($partner_id)
    {
        $this->partner_id = $partner_id;
        return $this;
    }


    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


    /**
     * @return $this
     * @throws InvalidSteamPartnerIdException
     * @throws InvalidSteamTokenException
     */
    public function create()
    {
        if ($this->partner_id != null && $this->token != null) {
            if (!preg_match('/^' . $this->partner_id_regex_base . '$/', $this->partner_id)) {
                throw new InvalidSteamPartnerIdException($this->partner_id);
            }

            if (!preg_match('/^' . $this->token_regex_base . '$/', $this->token)) {
                throw new InvalidSteamTokenException($this->token);
            }

            $this->full_url = $this->base_url . $this->prefix . 'partner=' . $this->partner_id . '&token=' . $this->token;
        } else {
            $this->full_url = null;
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getFullURL()
    {
        return $this->full_url;
    }


    /**
     * @param string $full_url
     * @return $this
     */
    public function setFullURL($full_url)
    {
        $this->full_url = $full_url;
        return $this;
    }


    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->full_url == null) {
            return false;
        }

        return preg_match('/^' . $this->escapeSlashes($this->base_url . $this->prefix) . '/', $this->full_url)
            && preg_match($this->partner_id_regex, $this->full_url)
            && preg_match($this->token_regex, $this->full_url);
    }


    /**
     * @return $this
     * @throws NotASteamTradeURLException
     * @throws SteamPartnerIdNotFoundException
     * @throws SteamTokenNotFoundException
     */
    public function decompose()
    {
        if ($this->full_url != null) {
            if (!preg_match('/^' . $this->escapeSlashes($this->base_url . $this->prefix) . '/', $this->full_url)) {
                throw new NotASteamTradeURLException($this->full_url);
            }

            $partner_matches = [];
            if (!preg_match($this->partner_id_regex, $this->full_url, $partner_matches)) {
                throw new SteamPartnerIdNotFoundException($this->full_url);
            }

            $token_matches = [];
            if (!preg_match($this->token_regex, $this->full_url, $token_matches)) {
                throw new SteamTokenNotFoundException($this->full_url);
            }

            $this->partner_id = $partner_matches[1];
            $this->token = $token_matches[1];
        } else {
            $this->partner_id = null;
            $this->token = null;
        }

        return $this;
    }


    /**
     * @param string $string
     * @return mixed
     */
    private function escapeSlashes($string)
    {
        return str_replace('/', '\/', $string);
    }


    /**
     * @return string
     */
    public function getPartnerId()
    {
        return $this->partner_id;
    }


    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @param string $trade_offer_id
     * @return string|null
     */
    public function getTradeOfferURLFromOfferId($trade_offer_id)
    {
        if ($trade_offer_id === null || empty(trim($trade_offer_id))) {
            return null;
        }

        return $this->base_url . '/' . $trade_offer_id;
    }
}