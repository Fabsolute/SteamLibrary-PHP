<?php

namespace Fabs\SteamLibrary\Game;

use Fabs\SteamLibrary\Model\Item\SteamInventoryModel;
use Fabs\SteamLibrary\Model\Item\ItemModelSteam;
use Fabs\SteamLibrary\Model\Item\SteamStickerModel;
use GuzzleHttp\Client;

class Inventory
{
    const BASE_IMAGE_URL = 'http://cdn.steamcommunity.com/economy/image/';

    /**
     * @param $partner_id string|int
     * @param $game_id string
     * @param $game_context string
     * @return ItemModelSteam[]
     */
    protected static function getSteamItemsFromPartnerID($partner_id, $game_id, $game_context)
    {
        $inventory = self::getSteamInventoryFromPartnerID($partner_id, $game_id, $game_context);
        return self::getSteamItemsFromInventory($inventory);
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
    public static function getSteamIDFromPartnerID($partner_id)
    {
        return '765' . (intval($partner_id) + 61197960265728);
    }

    /**
     * @param $steam_id string
     * @param $game_id string
     * @param $game_context string
     * @return ItemModelSteam[]
     */
    protected static function getSteamItemsFromSteamID($steam_id, $game_id, $game_context)
    {
        $inventory = self::getSteamInventoryFromSteamID($steam_id, $game_id, $game_context);
        return self::getSteamItemsFromInventory($inventory);
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
     * @return ItemModelSteam[]
     */
    private static function getSteamItemsFromInventory($inventory)
    {
        $steam_items = [];
        foreach ($inventory->assets as $asset) {
            $steam_item = new ItemModelSteam();
            $steam_item->assetid = $asset->assetid;
            foreach ($inventory->descriptions as $description) {
                if ($asset->classid === $description->classid && $asset->instanceid === $description->instanceid) {
                    $steam_item->description = $description;
                    break;
                }
            }
            if ($steam_item->description != null) {
                if ($steam_item->description->icon_url != null) {
                    $steam_item->description->icon_url = self::BASE_IMAGE_URL
                        . $steam_item->description->icon_url;
                }

                if ($steam_item->description->icon_url_large != null) {
                    $steam_item->description->icon_url_large = self::BASE_IMAGE_URL
                        . $steam_item->description->icon_url_large;
                }

                foreach ($steam_item->description->tags as $tag) {
                    if ($tag->category == 'Type') {
                        $steam_item->type = $tag->internal_name;
                        break;
                    }
                }

                $steam_item->stickers = [];

                foreach ($steam_item->description->descriptions as $item_description) {
                    if (strpos($item_description->value, 'sticker_info') !== false) {
                        $sticker_description = $item_description->value;
                        preg_match("/<center>(.*)<\\/center>/", $sticker_description, $center_value_array);
                        if (count($center_value_array) > 1) {
                            $center_value = $center_value_array[1];
                            preg_match_all("/<img width=64 height=48 src=\"(.*?)\">/",
                                $center_value, $sticker_images_array);
                            if (count($sticker_images_array) > 1) {
                                $sticker_images = $sticker_images_array[1];
                                preg_match("/<br>Sticker:(.*?)$/", $center_value, $sticker_names_array);
                                if (count($sticker_names_array) > 1) {
                                    $sticker_names = explode(', ', $sticker_names_array[1]);
                                    for ($i = 0; $i < count($sticker_names); $i++) {
                                        $sticker_name = $sticker_names[$i];
                                        if (count($sticker_images) > $i) {
                                            $sticker_image = $sticker_images[$i];

                                            $sticker = new SteamStickerModel();
                                            $sticker->name = $sticker_name;
                                            $sticker->image = $sticker_image;

                                            $steam_item->stickers[] = $sticker;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
            $steam_items[] = $steam_item;
        }

        return $steam_items;
    }
}