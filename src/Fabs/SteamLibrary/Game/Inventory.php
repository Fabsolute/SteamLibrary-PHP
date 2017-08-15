<?php

namespace Fabs\SteamLibrary\Game;

use Fabs\SteamLibrary\Exception\GeneralSteamException;
use Fabs\SteamLibrary\Exception\InvalidSteamInventoryException;
use Fabs\SteamLibrary\Exception\TooManyRequestException;
use Fabs\SteamLibrary\Model\Item\SteamInventoryModel;
use Fabs\SteamLibrary\Model\Item\ItemModel;
use Fabs\SteamLibrary\Model\Item\SteamStickerModel;
use Fabs\SteamLibrary\Player\PlayerProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Inventory
{
    /** @var string */
    public static $proxy_url = null;
    /** @var int */
    public static $proxy_port = 0;
    /** @var string */
    public static $proxy_username_password = null;

    const BASE_IMAGE_URL = 'https://steamcommunity-a.akamaihd.net/economy/image/';

    /**
     * @param $partner_id string|int
     * @param $game_id string
     * @param $game_context string
     * @return ItemModel[]
     * @throws GeneralSteamException
     * @throws InvalidSteamInventoryException
     * @throws TooManyRequestException
     */
    protected static function getSteamItemsFromPartnerID($partner_id, $game_id, $game_context)
    {
        $steam_id = PlayerProfile::getSteamIDFromPartnerID($partner_id);
        return self::getSteamItemsFromSteamID($steam_id, $game_id, $game_context);
    }


    /**
     * @param $steam_id string
     * @param $game_id string
     * @param $game_context string
     * @return ItemModel[]
     * @throws GeneralSteamException
     * @throws InvalidSteamInventoryException
     * @throws TooManyRequestException
     */
    protected static function getSteamItemsFromSteamID($steam_id, $game_id, $game_context)
    {
        $inventory = self::getSteamInventoryFromSteamID($steam_id, $game_id, $game_context);
        return self::getSteamItemsFromInventory($inventory, $steam_id);
    }

    /**
     * @param $steam_id string
     * @param $game_id string
     * @param $game_context string
     * @return SteamInventoryModel
     * @throws GeneralSteamException
     * @throws InvalidSteamInventoryException
     * @throws TooManyRequestException
     */
    private static function getSteamInventoryFromSteamID($steam_id, $game_id, $game_context)
    {
        try {

            $url = sprintf('https://steamcommunity.com/inventory/%s/%s/%s',
                $steam_id, (string)$game_id, (string)$game_context);

            $config = [];
            if (self::$proxy_url !== null) {
                $config['curl'] = [
                    CURLOPT_PROXY => self::$proxy_url,
                    CURLOPT_PROXYPORT => self::$proxy_port,
                    CURLOPT_PROXYUSERPWD => self::$proxy_username_password,
                ];
                $config['proxy'] = self::$proxy_url;
            }

            $client = new Client($config);
            $json_content = $client->get($url)->getBody()->getContents();
            $content = json_decode($json_content, true);
            /** @var SteamInventoryModel $object */
            $object = SteamInventoryModel::deserialize($content);
            return $object;
        } catch (RequestException $exception) {
            if ($exception->getResponse() !== null) {
                switch ($exception->getResponse()->getStatusCode()) {
                    case 403:
                    case 404:
                        throw new InvalidSteamInventoryException($exception->getRequest()->getUri()->getPath());
                    case 429:
                        throw new TooManyRequestException($exception->getRequest()->getUri()->getPath());
                    case 500:
                        throw new GeneralSteamException($exception->getRequest()->getUri()->getPath());
                    default:
                        break;
                }
            }

            throw $exception;
        }
    }

    /**
     * @param $inventory SteamInventoryModel
     * @param $owner_steam_id string
     * @return \Fabs\SteamLibrary\Model\Item\ItemModel[]
     */
    private static function getSteamItemsFromInventory($inventory, $owner_steam_id)
    {
        $steam_items = [];
        foreach ($inventory->assets as $asset) {
            $steam_item = new ItemModel();
            $steam_item->assetid = $asset->assetid;

            foreach ($inventory->descriptions as $description) {
                if ($asset->classid === $description->classid && $asset->instanceid === $description->instanceid) {
                    $steam_item->description = $description;
                    break;
                }
            }
            if ($steam_item->description != null) {
                if ($steam_item->description->icon_url != null) {
                    if (strpos($steam_item->description->icon_url, self::BASE_IMAGE_URL) === false) {
                        $steam_item->description->icon_url = self::BASE_IMAGE_URL
                            . $steam_item->description->icon_url;
                    }
                }

                if ($steam_item->description->icon_url_large != null) {
                    if (strpos($steam_item->description->icon_url_large, self::BASE_IMAGE_URL) === false) {
                        $steam_item->description->icon_url_large = self::BASE_IMAGE_URL
                            . $steam_item->description->icon_url_large;
                    }
                }

                foreach ($steam_item->description->tags as $tag) {
                    if ($tag->category == 'Type') {
                        $steam_item->type = $tag->internal_name;
                    } elseif ($tag->category === 'Rarity') {
                        $steam_item->rarity_color = $tag->color;
                        $steam_item->rarity_name = $tag->internal_name;
                    } elseif ($tag->category === 'Exterior') {
                        $steam_item->exterior = $tag->internal_name;
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
                                            $sticker->name = trim($sticker_name);
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

                foreach ($steam_item->description->actions as $action) {
                    if ($action->name === 'Inspect in Game...') {

                        $steam_item->inspect_in_game_link = str_replace('%owner_steamid%', $owner_steam_id,
                            str_replace('%assetid%', $steam_item->assetid, $action->link)
                        );
                        break;
                    }
                }

                if ($steam_item->description->fraudwarnings != null) {
                    foreach ($steam_item->description->fraudwarnings as $fraud_warning) {
                        if (strpos($fraud_warning, 'Name Tag') !== false) {
                            $steam_item->name_tag = preg_replace("/Name Tag: ''(.*?)''/", "$1", $fraud_warning);
                            break;
                        }
                    }
                }
            }
            $steam_items[] = $steam_item;
        }

        return $steam_items;
    }
}