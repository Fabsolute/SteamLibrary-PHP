<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 17/06/2017
 * Time: 15:29
 */

namespace Fabs\SteamLibrary\Exception;


class InvalidSteamInventoryException extends SteamLibraryException
{

    public function __construct($url)
    {
        parent::__construct('Cannot get inventory for url ' . $url);
    }
}