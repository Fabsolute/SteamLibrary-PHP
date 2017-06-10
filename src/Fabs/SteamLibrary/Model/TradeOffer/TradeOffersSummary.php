<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 18:28
 */

namespace Fabs\SteamLibrary\Model\TradeOffer;


use Fabs\Serialize\SerializableObject;

class TradeOffersSummary extends SerializableObject
{
    /** @var int */
    public $pending_received_count = 0;
    /** @var int */
    public $new_received_count = 0;
    /** @var int */
    public $updated_received_count = 0;
    /** @var int */
    public $historical_received_count = 0;
    /** @var int */
    public $pending_sent_count = 0;
    /** @var int */
    public $newly_accepted_sent_count = 0;
    /** @var int */
    public $updated_sent_count = 0;
    /** @var int */
    public $historical_sent_count = 0;
    /** @var int */
    public $escrow_received_count = 0;
    /** @var int */
    public $escrow_sent_count = 0;

}