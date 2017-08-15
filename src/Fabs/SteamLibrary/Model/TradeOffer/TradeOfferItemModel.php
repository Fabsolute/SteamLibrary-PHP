<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 17:25
 */

namespace Fabs\SteamLibrary\Model\TradeOffer;

use Fabs\SteamLibrary\Model\Item\SteamAssetModel;

class TradeOfferItemModel extends SteamAssetModel
{
    /** @var bool */
    public $missing = false;
}