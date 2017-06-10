<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 10/06/2017
 * Time: 16:23
 */

namespace Fabs\SteamLibrary;


use Fabs\SteamLibrary\Constant\TradeOfferStates;
use Fabs\SteamLibrary\Model\APIResponseModel;
use Fabs\SteamLibrary\Model\TradeOffer\TradeOfferResponseModel;
use Fabs\SteamLibrary\Model\TradeOffer\TradeOffersResponseModel;
use Fabs\SteamLibrary\Model\TradeOffer\TradeOffersSummary;
use GuzzleHttp\Client;

class TradeOfferHandler
{
    const BaseAPIURL = "http://api.steampowered.com/IEconService";
    /** @var string */
    protected $api_key = null;
    protected $language = 'en_us';

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @param string $api_key
     * @return TradeOfferHandler
     */
    public function setAPIKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * @param string $language
     * @return TradeOfferHandler
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param string $trade_offer_id
     * @return TradeOfferResponseModel
     */
    public function getTradeOffer($trade_offer_id)
    {
        $response_model = $this->executeQuery('GetTradeOffer', ['tradeofferid' => $trade_offer_id]);

        return TradeOfferResponseModel::deserialize($response_model->response);
    }

    /**
     * @param $trade_offer_id
     * @return string
     */
    public function getTradeOfferStateByTradeOfferID($trade_offer_id)
    {
        $trade_offer = $this->getTradeOffer($trade_offer_id);
        if ($trade_offer != null && $trade_offer->offer != null) {
            return $this->getTradeOfferState($trade_offer->offer->trade_offer_state);
        }
        return TradeOfferStates::TradeOfferStateUnknown;
    }

    /**
     * @param int $state_number
     * @return string
     */
    public function getTradeOfferState($state_number)
    {
        $lookup[1] = TradeOfferStates::TradeOfferStateInvalid;
        $lookup[2] = TradeOfferStates::TradeOfferStateActive;
        $lookup[3] = TradeOfferStates::TradeOfferStateAccepted;
        $lookup[4] = TradeOfferStates::TradeOfferStateCountered;
        $lookup[5] = TradeOfferStates::TradeOfferStateExpired;
        $lookup[6] = TradeOfferStates::TradeOfferStateCanceled;
        $lookup[7] = TradeOfferStates::TradeOfferStateDeclined;
        $lookup[8] = TradeOfferStates::TradeOfferStateInvalidItems;
        $lookup[9] = TradeOfferStates::TradeOfferStateNeedsConfirmation;
        $lookup[10] = TradeOfferStates::TradeOfferStateCanceledBySecondFactor;
        $lookup[11] = TradeOfferStates::TradeOfferStateInEscrow;

        if (array_key_exists($state_number, $lookup)) {
            return $lookup[$state_number];
        }
        return TradeOfferStates::TradeOfferStateUnknown;
    }

    /**
     * @param bool $get_sent_offers
     * @param bool $get_received_offers ,
     * @param bool $get_description
     * @param bool $active_only
     * @param bool $historical_only
     * @param string $time_historical_cutoff
     * @return TradeOffersResponseModel
     */
    public function getTradeOffers($get_sent_offers, $get_received_offers, $get_description, $active_only, $historical_only, $time_historical_cutoff = "1389106496")
    {
        if ($get_sent_offers || $get_received_offers) {
            $options = [
                'get_sent_offers' => $get_sent_offers ? 'true' : 'false',
                'get_received_offers' => $get_received_offers ? 'true' : 'false',
                'get_description' => $get_description ? 'true' : 'false',
                'active_only' => $active_only ? 'true' : 'false',
                'historical_only' => $historical_only ? 'true' : 'false',
                'time_historical_cutoff' => $time_historical_cutoff
            ];
            $response = $this->executeQuery('GetTradeOffers', $options);
            return TradeOffersResponseModel::deserialize($response->response);
        }
        throw  new \InvalidArgumentException('get_sent_offers and get_received_offers cant be both false');
    }

    /**
     * @param string $time_historical_cutoff
     * @return TradeOffersResponseModel
     */
    public function getAllOffers($time_historical_cutoff = "1389106496")
    {
        return $this->getTradeOffers(true, true, false, true, true, $time_historical_cutoff);
    }

    /**
     * @param bool $get_sent_offers
     * @param bool $get_received_offers
     * @param bool $get_descriptions
     * @return TradeOffersResponseModel
     */
    public function getActiveTradeOffers($get_sent_offers, $get_received_offers, $get_descriptions)
    {
        return $this->getTradeOffers($get_sent_offers, $get_received_offers, $get_descriptions, true, false);
    }

    /**
     * @param string $time_last_visit
     * @return TradeOffersSummary
     */
    public function getTradeOffersSummary($time_last_visit)
    {
        $response = $this->executeQuery('GetTradeOffersSummary', ['time_last_visit' => $time_last_visit]);
        return TradeOffersSummary::deserialize($response->response);
    }

    /**
     * @param string $trade_offer_id
     * @return bool
     */
    public function declineTradeOffer($trade_offer_id)
    {
        try {
            $this->executeQuery('DeclineTradeOffer', ['tradeofferid' => $trade_offer_id], 'POST');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $trade_offer_id
     * @return bool
     */
    public function cancelTradeOffer($trade_offer_id)
    {
        try {
            $this->executeQuery('CancelTradeOffer', ['tradeofferid' => $trade_offer_id], 'POST');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $api
     * @param string[] $query_parameters
     * @param string $type
     * @return APIResponseModel
     */
    protected function executeQuery($api, $query_parameters, $type = 'GET', $body = [])
    {
        $custom_parameters = ['key' => $this->api_key, 'language' => $this->language];
        $parameters = array_merge($custom_parameters, $query_parameters);
        $query = http_build_query($parameters, null, '&');

        $url = sprintf('%s/%s/v1/?%s', self::BaseAPIURL, $api, $query);

        $client = new Client();

        if ($type === 'GET') {
            $response = $client->get($url);
        } else {
            $response = $client->post($url, $body);
        }

        $json_content = $response->getBody()->getContents();
        $content = json_decode($json_content, true);

        /** @var APIResponseModel $response_model */
        $response_model = APIResponseModel::deserialize($content);
        return $response_model;
    }
}