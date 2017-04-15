<?php

namespace Fabs\SteamLibrary\Game;

use Fabs\SteamLibrary\Model\SteamInventoryModel;
use GuzzleHttp\Client;

class InventoryBase
{
    /**
     * @param $partner_id string|int
     * @param $game_id string
     * @param $game_context string
     * @return SteamInventoryModel
     */
    protected static function getSteamInventoryFromPartnerID($partner_id, $game_id, $game_context)
    {
        $steam_id = self::getSteamIDFromPartnerID($partner_id);
        return self::getSteamInventoryFromSteamID($steam_id, $game_id, $game_context);
    }

    /**
     * @param $partner_id string|int
     * @return string
     */
    public static function getSteamIDFromPartnerID($partner_id) : string
    {
        return '765' . (intval($partner_id) + 61197960265728);
    }

    /**
     * @param $steam_id string
     * @param $game_id string
     * @param $game_context string
     * @return SteamInventoryModel
     */
    protected static function getSteamInventoryFromSteamID($steam_id, $game_id, $game_context)
    {
        $url = sprintf('https://steamcommunity.com/inventory/%s/%s/%s',
            $steam_id, (string)$game_id, (string)$game_context);
        $client = new Client();
        $json_content = $client->get($url)->getBody()->getContents();
        $content = json_decode($json_content, true);
        /** @var SteamInventoryModel $object */
        $object = SteamInventoryModel::deserialize($content);
        return $object;
    }
}