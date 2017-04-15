<?php

namespace Fabs\SteamLibrary\Game\CSGO;

use Fabs\SteamLibrary\Game\InventoryBase;
use Fabs\SteamLibrary\Model\CSGO\CSGOFloatAPIModel;
use GuzzleHttp\Client;

class Inventory extends InventoryBase
{
    const GameID = "730";
    const ContextID = "2";

    /**
     * @param $partner_id string|int
     * @return \Fabs\SteamLibrary\Model\SteamInventoryModel
     */
    public static function getInventoryFromPartnerID($partner_id)
    {
        return self::getSteamInventoryFromPartnerID($partner_id, self::GameID, self::ContextID);
    }

    /**
     * @param $steam_id string
     * @return \Fabs\SteamLibrary\Model\SteamInventoryModel
     */
    public static function getInventoryFromSteamID($steam_id)
    {
        return self::getSteamInventoryFromSteamID($steam_id, self::GameID, self::ContextID);
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