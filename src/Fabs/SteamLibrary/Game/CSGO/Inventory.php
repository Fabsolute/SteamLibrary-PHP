<?php

namespace Fabs\SteamLibrary\Game\CSGO;

use Fabs\SteamLibrary\Game\InventoryBase;
use Fabs\SteamLibrary\Model\CSGO\CSGOFloatAPIModel;
use Fabs\SteamLibrary\SteamTradeURLHandler;
use GuzzleHttp\Client;

class Inventory extends InventoryBase
{
    const GameID = "730";
    const ContextID = "2";

    /**
     * @param string $trade_url
     * @param $include_cases bool
     * @return \Fabs\SteamLibrary\Model\SteamItemModel[]
     */
    public function getItemsFromTradeURL($trade_url, $include_cases = false)
    {
        $partner_id = (new SteamTradeURLHandler())
            ->setFullURL($trade_url)
            ->decompose()
            ->getPartnerId();

        return self::getItemsFromPartnerID($partner_id, $include_cases);
    }

    /**
     * @param $partner_id string|int
     * @param $include_cases bool
     * @return \Fabs\SteamLibrary\Model\SteamItemModel[]
     */
    public static function getItemsFromPartnerID($partner_id, $include_cases = false)
    {
        return self::getSteamItemsFromPartnerID($partner_id, self::GameID, self::ContextID, $include_cases);
    }

    /**
     * @param $steam_id string
     * @param $include_cases bool
     * @return \Fabs\SteamLibrary\Model\SteamItemModel[]
     */
    public static function getInventoryFromSteamID($steam_id, $include_cases = false)
    {
        return self::getSteamItemsFromSteamID($steam_id, self::GameID, self::ContextID, $include_cases);
    }

    /**
     * @param $inspect_in_game_link string
     * @return \Fabs\SteamLibrary\Model\CSGO\CSGOFloatItemInfoModel
     */
    public static function getItemInfoFromInspectLink($inspect_in_game_link)
    {
        $url = sprintf('https://api.csgofloat.com:1738/?url=%s', $inspect_in_game_link);
        $client = new Client();
        $json_content = $client->get($url)->getBody()->getContents();
        $content = json_decode($json_content, true);
        /** @var CSGOFloatAPIModel $float_api */
        $float_api = CSGOFloatAPIModel::deserialize($content);
        return $float_api->iteminfo;
    }
}