<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 19/05/2017
 * Time: 11:26
 */

namespace Fabs\SteamLibrary\Model\Item;


use Fabs\Serialize\SerializableObject;

class SteamStickerModel extends SerializableObject
{
    /** @var string */
    public $name = null;
    /** @var string */
    public $image = null;
}