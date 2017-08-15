<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 16:42
 */

namespace Fabs\SteamLibrary\Model\TradeOffer;


use Fabs\Serialize\SerializableObject;

class TradeOfferModel extends SerializableObject
{
    /** @var string */
    public $tradeofferid = null;
    /** @var int */
    public $accountid_other = 0;
    /** @var string */
    public $message = null;
    /** @var int */
    public $expiration_time = 0;
    /** @var int */
    public $trade_offer_state = 0;
    /** @var TradeOfferItemModel[] */
    public $items_to_give = [];
    /** @var TradeOfferItemModel[] */
    public $items_to_receive = [];
    /** @var bool */
    public $is_our_offer = true;
    /** @var int */
    public $time_created = 0;
    /** @var int */
    public $time_updated = 0;
    /** @var bool */
    public $from_real_time_trade = false;
    /** @var int */
    public $escrow_end_date = 0;
    /** @var int */
    public $confirmation_method = 0;

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('items_to_give', TradeOfferItemModel::class, true);
        $this->registerProperty('items_to_receive', TradeOfferItemModel::class, true);
    }
}