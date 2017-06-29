<?php

namespace Fabs\SteamLibrary;


class SteamLoginConfig
{
    /** @var  string */
    private $host;
    /** @var  string */
    private $returnUrl;

    /**
     * @param string $host
     * @return SteamLoginConfig
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $returnUrl
     * @return SteamLoginConfig
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

}