<?php


namespace Fabs\SteamLibrary\Model\Player;


use Fabs\Serialize\SerializableObject;

class OwnedGameModel extends SerializableObject
{

    /** @var int */
    public $appid = 0;
    /** @var int */
    public $playtime_forever = 0;
    /** @var int */
    public $playtime_2weeks = 0;
}