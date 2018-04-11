<?php


namespace Fabs\SteamLibrary;


use Fabs\SteamLibrary\Exception\GeneralSteamException;
use Fabs\SteamLibrary\Exception\BadGatewayException;
use Fabs\SteamLibrary\Exception\TooManyRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;

class SteamRequest
{

    /** @var string */
    public static $proxy_url = null;
    /** @var int */
    public static $proxy_port = 0;
    /** @var string */
    public static $proxy_username_password = null;
    /** @var array */
    public static $cookie = [];

    const BASE_IMAGE_URL = 'https://steamcommunity-a.akamaihd.net/economy/image/';

    /**
     * @param string $url
     * @param bool $do_not_proxy
     * @param bool $json_decode
     * @return mixed
     * @throws BadGatewayException
     * @throws GeneralSteamException
     * @throws TooManyRequestException
     * @author necipallef <necipallef@gmail.com>
     */
    public static function get($url, $do_not_proxy = false, $json_decode = true)
    {

        $config = [];
        if ($do_not_proxy !== true) {
            if (self::$proxy_url !== null) {
                $config['curl'] = [
                    CURLOPT_PROXY => self::$proxy_url,
                    CURLOPT_PROXYPORT => self::$proxy_port,
                    CURLOPT_PROXYUSERPWD => self::$proxy_username_password,
                ];
                $config['proxy'] = self::$proxy_url;

            }
        }

        $config['cookies'] = CookieJar::fromArray(self::$cookie, 'steamcommunity.com');
        $client = new Client($config);

        $options =
            [
                'allow_redirects' =>
                    [
                        'max' => 10
//                        , 'track_redirects' => true
//                        , 'on_redirect' => SteamRequest::class . '::onRedirect'
                    ]
            ];
        try {
            $content = $client->get($url, $options)->getBody()->getContents();
            if ($json_decode === true) {
                return json_decode($content, true);
            }

            return $content;
        } catch (RequestException $exception) {
            if ($exception->getResponse() === null) {
                throw $exception;
            }

            switch ($exception->getResponse()->getStatusCode()) {
                case 429:
                    throw new TooManyRequestException($exception->getRequest()->getUri()->getPath());
                case 500:
                    throw new GeneralSteamException($exception->getRequest(), $exception->getResponse());
                case 502:
                    throw new BadGatewayException($exception->getRequest(), $exception->getResponse());
                default:
                    throw $exception;
            }
        }
    }

//    public static function onRedirect($request, $response, $uri)
//    {
//        static $count = 1;
//        $str_request = var_export($request, true);
//        $str_response = var_export($response, true);
//        $str_uri = var_export($uri, true);
//        file_put_contents('steam_request' . $count . '.txt', $str_request . PHP_EOL, FILE_APPEND);
//        file_put_contents('steam_request' . $count . '.txt', $str_response . PHP_EOL, FILE_APPEND);
//        file_put_contents('steam_request' . $count . '.txt', $str_uri . PHP_EOL, FILE_APPEND);
//        $count++;
//    }

    /**
     * @param string $url
     * @param mixed $body
     * @param bool $do_not_proxy
     * @return mixed
     * @author necipallef <necipallef@gmail.com>
     */
    public static function post($url, $body, $do_not_proxy = false)
    {

        $config = [];
        if ($do_not_proxy !== true) {
            if (self::$proxy_url !== null) {
                $config['curl'] = [
                    CURLOPT_PROXY => self::$proxy_url,
                    CURLOPT_PROXYPORT => self::$proxy_port,
                    CURLOPT_PROXYUSERPWD => self::$proxy_username_password,
                ];
                $config['proxy'] = self::$proxy_url;

            }
        }

        $client = new Client($config);
        $json_content = $client->post($url, $body)->getBody()->getContents();
        return json_decode($json_content, true);
    }
}