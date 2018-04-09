<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:47
 */

namespace Fabs\SteamLibrary\Model\Item;

use Fabs\Serialize\SerializableObject;

class SteamDescriptionModel extends SerializableObject
{
    /** @var int */
    public $appid = 0;
    /** @var string */
    public $classid = null;
    /** @var string */
    public $instanceid = null;
    /** @var int */
    public $currency = 0;
    /** @var string */
    public $background_color = null;
    /** @var string */
    public $icon_url = null;
    /** @var string */
    public $icon_url_large = null;
    /** @var SteamDescriptionDescriptionModel[] */
    public $descriptions = [];
    /** @var SteamDescriptionDescriptionModel[] */
    public $owner_descriptions = [];
    /** @var int */
    public $tradable = 0;
    /** @var SteamDescriptionActionModel[] */
    public $actions = [];
    /** @var string */
    public $name = null;
    /** @var string */
    public $name_color = null;
    /** @var string */
    public $type = null;
    /** @var string */
    public $market_name = null;
    /** @var string */
    public $market_hash_name = null;
    /** @var SteamDescriptionActionModel[] */
    public $market_actions = [];
    /** @var int */
    public $commodity = 0;
    /** @var int */
    public $market_tradable_restriction = 0;
    /** @var int */
    public $marketable = 0;
    /** @var SteamDescriptionTagModel[] */
    public $tags = [];
    /** @var string[] */
    public $fraudwarnings = [];

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('descriptions', SteamDescriptionDescriptionModel::class, true);
        $this->registerProperty('owner_descriptions', SteamDescriptionDescriptionModel::class, true);
        $this->registerProperty('actions', SteamDescriptionActionModel::class, true);
        $this->registerProperty('market_actions', SteamDescriptionActionModel::class, true);
        $this->registerProperty('tags', SteamDescriptionTagModel::class, true);
    }

}