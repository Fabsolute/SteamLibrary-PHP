<?php


namespace Fabs\SteamLibrary;


use GuzzleHttp\Client;

class SteamRequest
{

    /** @var string */
    public static $proxy_url = null;
    /** @var int */
    public static $proxy_port = 0;
    /** @var string */
    public static $proxy_username_password = null;

    const BASE_IMAGE_URL = 'https://steamcommunity-a.akamaihd.net/economy/image/';

    /**
     * @param string $url
     * @return mixed
     * @author necipallef <necipallef@gmail.com>
     */
    public static function get($url)
    {

        $config = [];
        if (self::$proxy_url !== null) {
            $config['curl'] = [
                CURLOPT_PROXY => self::$proxy_url,
                CURLOPT_PROXYPORT => self::$proxy_port,
                CURLOPT_PROXYUSERPWD => self::$proxy_username_password,
            ];
            $config['proxy'] = self::$proxy_url;
        }

        $client = new Client($config);
        $json_content = $client->get($url)->getBody()->getContents();
        return json_decode($json_content, true);
    }


    /**
     * @param string $url
     * @return mixed
     * @author necipallef <necipallef@gmail.com>
     */
    public static function post($url)
    {

        $config = [];
        if (self::$proxy_url !== null) {
            $config['curl'] = [
                CURLOPT_PROXY => self::$proxy_url,
                CURLOPT_PROXYPORT => self::$proxy_port,
                CURLOPT_PROXYUSERPWD => self::$proxy_username_password,
            ];
            $config['proxy'] = self::$proxy_url;
        }

        $client = new Client($config);
        $json_content = $client->post($url)->getBody()->getContents();
        return json_decode($json_content, true);
    }
}