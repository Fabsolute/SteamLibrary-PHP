<?php


namespace Fabs\SteamLibrary\Exception;


class BadGatewayException extends SteamLibraryException
{

    public function __construct($request_url, $response)
    {
        if ($response === null){
            $response = '';
        }

        parent::__construct('Bad gateway exception for request url ' . $request_url . ', response ' . $response);
    }
}