<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:46
 */

namespace Fabs\SteamLibrary\Model\Item;

use Fabs\Serialize\SerializableObject;

class SteamInventoryModel extends SerializableObject
{
    /** @var SteamAssetModel[] */
    public $assets = [];
    /** @var SteamDescriptionModel[] */
    public $descriptions = [];
    /** @var int */
    public $total_inventory_count = 0;
    /** @var int */
    public $success = 0;
    /** @var int */
    public $rwgrsn = 0;

    public function __construct()
    {
        parent::__construct();

        $this->registerProperty('assets', SteamAssetModel::class, true);
        $this->registerProperty('descriptions', SteamDescriptionModel::class, true);
    }
}