<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 17/07/2017
 * Time: 13:59
 */

namespace Fabs\SteamLibrary\Exception;


class GeneralSteamException extends SteamLibraryException
{

    public function __construct($url)
    {
        parent::__construct('General steam exception for url ' . $url);
    }
}