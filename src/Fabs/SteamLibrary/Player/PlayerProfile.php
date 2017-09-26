<?php

namespace Fabs\SteamLibrary\Player;

use Fabs\SteamLibrary\Constant\ISteamUser;
use Fabs\SteamLibrary\Exception\BadGatewayException;
use Fabs\SteamLibrary\Exception\GeneralSteamException;
use Fabs\SteamLibrary\Exception\TooManyRequestException;
use Fabs\SteamLibrary\Model\APIResponseModel;
use Fabs\SteamLibrary\Model\Player\PlayerProfileModel;
use Fabs\SteamLibrary\Model\Player\PlayerProfilesModel;
use Fabs\SteamLibrary\SteamRequest;
use Fabs\SteamLibrary\SteamTradeURLHandler;

class PlayerProfile
{
    /**
     * @param string $steam_api_key
     * @param string[] $user_steam_ids
     * @throws BadGatewayException
     * @throws GeneralSteamException
     * @throws TooManyRequestException
     * @return PlayerProfileModel[]
     */
    public static function getUserProfiles($steam_api_key, $user_steam_ids)
    {
        $user_steam_ids_string = implode(',', $user_steam_ids);
        $uri = sprintf('%s/?key=%s&steamids=%s',
            ISteamUser::GET_PLAYER_SUMMARIES_URL,
            $steam_api_key,
            $user_steam_ids_string);

        $content = SteamRequest::get($uri);
        $response_model = APIResponseModel::deserialize($content);
        /** @var PlayerProfilesModel $player_profiles_model */
        $player_profiles_model = PlayerProfilesModel::deserialize($response_model->response);
        $player_profile_model_look_up = [];
        foreach ($player_profiles_model->players as $player_profile_model)
        {
            $player_profile_model_look_up[$player_profile_model->steamid] = $player_profile_model;
        }

        return $player_profile_model_look_up;
    }

    /**
     * @param string $steam_api_key
     * @param string $user_steam_id
     * @throws BadGatewayException
     * @throws GeneralSteamException
     * @throws TooManyRequestException
     * @return PlayerProfileModel|null
     */
    public static function getUserProfile($steam_api_key, $user_steam_id)
    {
        $player_profiles = self::getUserProfiles($steam_api_key, [$user_steam_id]);
        if (array_key_exists($user_steam_id, $player_profiles))
        {
            return $player_profiles[$user_steam_id];
        } else
        {
            return null;
        }
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
     * @param string $steam_id
     * @return string
     */
    public static function getPartnerIDFromSteamID($steam_id)
    {
        return strval(intval(substr($steam_id, 3)) - 61197960265728);
    }


    /**
     * @param string $trade_url
     * @return string
     */
    public static function getUserProfileURLFromTradeURL($trade_url)
    {
        $partner_id = (new SteamTradeURLHandler())
            ->setFullURL($trade_url)
            ->decompose()
            ->getPartnerId();

        return self::getUserProfileURLFromPartnerId($partner_id);
    }

    
    /**
     * @param string $partner_id
     * @return string
     */
    public static function getUserProfileURLFromPartnerId($partner_id)
    {
        $steam_id = self::getSteamIDFromPartnerID($partner_id);
        return self::getUserProfileURLFromSteamId($steam_id);
    }


    /**
     * @param string $steam_id
     * @return string
     */
    public static function getUserProfileURLFromSteamId($steam_id)
    {
        $base_url = 'http://steamcommunity.com/profiles/';
        return $base_url . $steam_id;
    }
}