<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 16:39
 */

namespace Fabs\SteamLibrary\Model\TradeOffer;

use Fabs\Serialize\SerializableObject;
use Fabs\SteamLibrary\Model\Item\SteamDescriptionModel;

class TradeOfferResponseModel extends SerializableObject
{
    /** @var TradeOfferModel */
    public $offer = null;
    /** @var SteamDescriptionModel[] */
    public $descriptions = [];

    public function __construct()
    {
        parent::__construct();

        $this->registerProperty('offer', TradeOfferModel::class);
        $this->registerProperty('descriptions', SteamDescriptionModel::class, true);
    }
}