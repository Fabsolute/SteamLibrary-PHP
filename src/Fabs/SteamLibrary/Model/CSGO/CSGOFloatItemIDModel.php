<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:49
 */

namespace Fabs\SteamLibrary\Model\CSGO;

use Fabs\Serialize\SerializableObject;

class CSGOFloatItemIDModel extends SerializableObject
{
    /** @var int */
    public $low = 0;
    /** @var int */
    public $high = 0;
    /** @var bool */
    public $unsigned = false;
}