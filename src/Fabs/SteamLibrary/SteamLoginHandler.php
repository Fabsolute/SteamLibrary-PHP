<?php

namespace Fabs\SteamLibrary;


use LightOpenID;

class SteamLoginHandler
{
    const STEAM_OPENID_URL = 'http://steamcommunity.com/openid';

    /** @var  string */
    private $host;
    /** @var  string */
    private $return_url;
    /** @var  LightOpenID */
    private $openid;
    /** @var  bool|null */
    private $is_open_id_validated = null;

    /**
     * SteamLoginHandler constructor.
     * @param string $host
     * @param string $return_url
     */
    public function __construct($host, $return_url)
    {
        $this->host = $host;
        $this->return_url = $return_url;
    }

    /**
     * @return LightOpenID
     */
    private function getOpenid()
    {
        if ($this->openid === null)
        {
            $this->openid = new LightOpenID($this->host);
            $this->openid->returnUrl = $this->return_url;
            $this->openid->identity = self::STEAM_OPENID_URL;
        }
        return $this->openid;
    }

    /**
     * @return string
     */
    public function generateAuthenticationURL()
    {
        return $this->getOpenid()->authUrl();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->is_open_id_validated === null)
        {
            $this->is_open_id_validated = $this->getOpenid()->validate();
        }

        return $this->is_open_id_validated;
    }

    /**
     * @return string|null
     */
    public function getSteamId()
    {
        if ($this->isValid())
        {
            $identity = $this->getOpenid()->identity;
            $pattern = '/^http:\/\/steamcommunity\.com\/openid\/id\/(?<steam_id>\d+)$/';
            $matches = [];
            preg_match($pattern, $identity, $matches);
            $steam_id = $matches['steam_id'];
            return $steam_id;
        }
        else
        {
            return null;
        }
    }
}