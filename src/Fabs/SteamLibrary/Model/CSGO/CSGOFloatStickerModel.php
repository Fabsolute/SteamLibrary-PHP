<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:50
 */

namespace Fabs\SteamLibrary\Model\CSGO;


use Fabs\Serialize\SerializableObject;

class CSGOFloatStickerModel extends SerializableObject
{
    /** @var int */
    public $slot = 0;
    /** @var int */
    public $sticker_id = 0;
    /** @var string */
    public $wear = null;
    /** @var string */
    public $scale = null;
    /** @var string */
    public $rotation = null;
}