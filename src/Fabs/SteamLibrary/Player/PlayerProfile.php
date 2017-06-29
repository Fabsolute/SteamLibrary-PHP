<?php

namespace Fabs\SteamLibrary\Player;

use Fabs\SteamLibrary\Constant\ISteamUser;
use Fabs\SteamLibrary\Model\APIResponseModel;
use Fabs\SteamLibrary\Model\Player\PlayerProfileModel;
use Fabs\SteamLibrary\Model\Player\PlayerProfilesModel;
use GuzzleHttp\Client;

class PlayerProfile
{
    /**
     * @param string $steam_api_key
     * @param string[] $user_steam_ids
     * @return PlayerProfileModel[]
     */
    public static function getUserProfiles($steam_api_key, $user_steam_ids)
    {
        $user_steam_ids_string = implode(',', $user_steam_ids);
        $uri = sprintf('%s/?key=%s&steamids=%s',
            ISteamUser::GET_PLAYER_SUMMARIES_URL,
            $steam_api_key,
            $user_steam_ids_string);

        $guzzle_client = new Client();
        $response = $guzzle_client->get($uri)->getBody()->getContents();
        /** @var APIResponseModel $response_model */
        $response_model = APIResponseModel::deserialize(json_decode($response, true));
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
     * @return PlayerProfileModel
     */
    public static function getUserProfile($steam_api_key, $user_steam_id)
    {
        return self::getUserProfiles($steam_api_key, [$user_steam_id])[$user_steam_id];
    }
}