<?php


namespace Fabs\SteamLibrary\Player;


use Fabs\SteamLibrary\Constant\IPlayerService;
use Fabs\SteamLibrary\Game\CSGO\Inventory;
use Fabs\SteamLibrary\Model\APIResponseModel;
use Fabs\SteamLibrary\Model\Player\PlayerOwnedGamesModel;
use GuzzleHttp\Client;

class PlayerOwnedGames
{

    /**
     * @param string $api_key
     * @param string $steam_id
     * @param string $format
     * @return PlayerOwnedGamesModel
     * @author necipallef <necipallef@gmail.com>
     */
    public static function getAllGames($api_key, $steam_id, $format = 'json')
    {
        $api_url = sprintf('%s?key=%s&steam_id=%s&format=%s',
            IPlayerService::GET_OWNED_GAMES_URL,
            $api_key,
            $steam_id,
            $format);

        $guzzle_client = new Client();
        $response = $guzzle_client->get($api_url)->getBody()->getContents();
        /** @var APIResponseModel $response_model */
        $response_model = APIResponseModel::deserialize(json_decode($response, true));
        /** @var PlayerOwnedGamesModel $player_owned_games_model */
        $player_owned_games_model = PlayerOwnedGamesModel::deserialize($response_model->response);
        return $player_owned_games_model;
    }


    public static function getPlayerCSGOTotalPlayTimeHours($api_key, $steam_id)
    {
        $player_owned_games_model = self::getAllGames($api_key, $steam_id);
        foreach ($player_owned_games_model->games as $game_model){
            if ($game_model->appid === intval(Inventory::GameID)){
                return $game_model->playtime_forever / 60;
            }
        }

        return 0;
    }
}