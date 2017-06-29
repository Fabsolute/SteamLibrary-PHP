<?php

namespace Fabs\SteamLibrary;


use LightOpenID;

class SteamLoginHandler
{
    const STEAM_OPENID_URL = 'http://steamcommunity.com/openid';

    /** @var  SteamLoginConfig */
    private $steam_login_config;
    /** @var  LightOpenID */
    private $openid;
    /** @var  bool|null */
    private $is_open_id_validated = null;

    /**
     * SteamLoginHandler constructor.
     * @param SteamLoginConfig $steam_login_config
     */
    public function __construct($steam_login_config)
    {
        $this->steam_login_config = $steam_login_config;
    }

    /**
     * @return SteamLoginConfig
     */
    public function getSteamLoginConfig()
    {
        return $this->steam_login_config;
    }

    /**
     * @return LightOpenID
     */
    private function getOpenid()
    {
        if ($this->openid === null)
        {
            $this->openid = new LightOpenID($this->steam_login_config->getHost());
            $this->openid->returnUrl = $this->steam_login_config->getReturnUrl();
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
     * @return int
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
        } else
        {
            return 0;
        }
    }
}