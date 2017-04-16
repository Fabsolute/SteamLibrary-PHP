<?php

namespace Fabs\SteamLibrary\Game;

use Fabs\SteamLibrary\Model\SteamInventoryModel;
use Fabs\SteamLibrary\Model\SteamItemModel;
use GuzzleHttp\Client;

class InventoryBase
{
    /**
     * @param $partner_id string|int
     * @param $game_id string
     * @param $game_context string
     * @param $include_cases bool
     * @return SteamItemModel[]
     */
    protected static function getSteamItemsFromPartnerID($partner_id, $game_id, $game_context, $include_cases)
    {
        $inventory = self::getSteamInventoryFromPartnerID($partner_id, $game_id, $game_context);
        return self::getSteamItemsFromInventory($inventory, $include_cases);
    }

    /**
     * @param $partner_id string|int
     * @param $game_id string
     * @param $game_context string
     * @return SteamInventoryModel
     */
    private static function getSteamInventoryFromPartnerID($partner_id, $game_id, $game_context)
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
     * @param bool $include_cases
     * @return SteamItemModel[]
     */
    protected static function getSteamItemsFromSteamID($steam_id, $game_id, $game_context, $include_cases)
    {
        $inventory = self::getSteamInventoryFromSteamID($steam_id, $game_id, $game_context);
        return self::getSteamItemsFromInventory($inventory, $include_cases);
    }

    /**
     * @param $steam_id string
     * @param $game_id string
     * @param $game_context string
     * @return SteamInventoryModel
     */
    private static function getSteamInventoryFromSteamID($steam_id, $game_id, $game_context)
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

    /**
     * @param $inventory SteamInventoryModel
     * @param $include_cases bool
     * @return SteamItemModel[]
     */
    private static function getSteamItemsFromInventory($inventory, $include_cases)
    {
        $steam_items = [];
        foreach ($inventory->assets as $asset)
        {
            if (!$include_cases)
            {
                if ($asset->instanceid === '0')
                {
                    continue;
                }
            }
            $steam_item = new SteamItemModel();
            $steam_item->assetid = $asset->assetid;
            foreach ($inventory->descriptions as $description)
            {
                if ($asset->classid === $description->classid && $asset->instanceid === $description->instanceid)
                {
                    $steam_item->description = $description;
                    break;
                }
            }
            $steam_items[] = $steam_item;
        }
        return $steam_items;
    }
}