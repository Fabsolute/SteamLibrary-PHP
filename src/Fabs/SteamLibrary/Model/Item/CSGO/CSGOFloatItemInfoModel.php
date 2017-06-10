<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:50
 */

namespace Fabs\SteamLibrary\Model\Item\CSGO;

use Fabs\Serialize\SerializableObject;

class CSGOFloatItemInfoModel extends SerializableObject
{
    /** @var string */
    public $accountid = null;
    /** @var string */
    public $itemid = null;
    /** @var int */
    public $defindex = 0;
    /** @var int */
    public $paintindex = 0;
    /** @var int */
    public $rarity = 0;
    /** @var int */
    public $quality = 0;
    /** @var int */
    public $paintwear = 0;
    /** @var int */
    public $paintseed = 0;
    /** @var string */
    public $killeaterscoretype = null;
    /** @var string */
    public $killeatervalue = null;
    /** @var string */
    public $customname = null;
    /** @var string */
    public $stickers = null;
    /** @var int */
    public $inventory = 0;
    /** @var int */
    public $origin = 0;
    /** @var string */
    public $questid = null;
    /** @var string */
    public $dropreason = null;
    /** @var float */
    public $floatvalue = 0;
    /** @var int */
    public $itemid_int = 0;
    /** @var int */
    public $s = 0;
    /** @var int */
    public $a = 0;
    /** @var int */
    public $d = 0;
    /** @var int */
    public $m = 0;
    /** @var string */
    public $imageurl = null;
    /** @var float */
    public $min = 0;
    /** @var float */
    public $max = 0;
    /** @var string */
    public $weapon_type = null;
    /** @var string */
    public $item_name = null;

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('itemid', CSGOFloatItemIDModel::class);
        $this->registerProperty('stickers', CSGOFloatStickerModel::class, true);
    }
}