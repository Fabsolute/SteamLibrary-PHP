<?php


namespace Fabs\SteamLibrary\Exception;


use Psr\Http\Message\RequestInterface;

class BadGatewayException extends SteamRequestException
{

    /**
     * BadGatewayException constructor.
     * @param RequestInterface $request
     * @param null|RequestInterface $response
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
    }
}