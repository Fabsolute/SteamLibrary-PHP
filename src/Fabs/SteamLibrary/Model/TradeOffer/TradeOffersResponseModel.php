<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 18:16
 */

namespace Fabs\SteamLibrary\Model\TradeOffer;


use Fabs\Serialize\SerializableObject;
use Fabs\SteamLibrary\Model\Item\SteamDescriptionModel;

class TradeOffersResponseModel extends SerializableObject
{
    /** @var TradeOfferModel[] */
    public $trade_offers_sent = [];
    /** @var TradeOfferModel[] */
    public $trade_offers_received = [];
    /** @var SteamDescriptionModel[] */
    public $descriptions = [];

    public function __construct()
    {
        parent::__construct();

        $this->registerProperty('trade_offers_sent', TradeOfferModel::class, true);
        $this->registerProperty('trade_offers_received', TradeOfferModel::class, true);
        $this->registerProperty('descriptions', SteamDescriptionModel::class, true);
    }
}