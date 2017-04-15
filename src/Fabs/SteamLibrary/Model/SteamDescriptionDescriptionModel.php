<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:47
 */

namespace Fabs\SteamLibrary\Model;

use Fabs\Serialize\SerializableObject;

class SteamDescriptionDescriptionModel extends SerializableObject
{
    /** @var string */
    public $type = null;
    /** @var string */
    public $value = null;
}