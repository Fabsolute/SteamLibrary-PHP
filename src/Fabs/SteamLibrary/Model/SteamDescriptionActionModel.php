<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:47
 */

namespace Fabs\SteamLibrary\Model;

use Fabs\Serialize\SerializableObject;

class SteamDescriptionActionModel extends SerializableObject
{
    /** @var string */
    public $link = null;
    /** @var string */
    public $name = null;
}