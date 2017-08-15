<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:49
 */

namespace Fabs\SteamLibrary\Model\Item\CSGO;

use Fabs\Serialize\SerializableObject;

class CSGOFloatAPIModel extends SerializableObject
{
    /** @var CSGOFloatItemInfoModel */
    public $iteminfo = null;

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('iteminfo', CSGOFloatItemInfoModel::class);
    }
}