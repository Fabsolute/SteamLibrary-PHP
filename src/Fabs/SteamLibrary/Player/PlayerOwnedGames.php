<?php


namespace Fabs\SteamLibrary\Player;


use Fabs\SteamLibrary\Constant\IPlayerService;
use Fabs\SteamLibrary\Exception\BadGatewayException;
use Fabs\SteamLibrary\Exception\GeneralSteamException;
use Fabs\SteamLibrary\Exception\TooManyRequestException;
use Fabs\SteamLibrary\Game\CSGO\Inventory;
use Fabs\SteamLibrary\Model\APIResponseModel;
use Fabs\SteamLibrary\Model\Player\PlayerOwnedGamesModel;
use Fabs\SteamLibrary\SteamRequest;

class PlayerOwnedGames
{

    /**
     * @param string $api_key
     * @param string $steam_id
     * @param string $format
     * @throws BadGatewayException
     * @throws GeneralSteamException
     * @throws TooManyRequestException
     * @return PlayerOwnedGamesModel|null
     * @author necipallef <necipallef@gmail.com>
     */
    public static function getAllGames($api_key, $steam_id, $format = 'json')
    {
        $api_url = sprintf('%s?key=%s&steamid=%s&format=%s',
            IPlayerService::GET_OWNED_GAMES_URL,
            $api_key,
            $steam_id,
            $format);

        $content = SteamRequest::get($api_url);
        $response_model = APIResponseModel::deserialize($content);
        /** @var PlayerOwnedGamesModel $player_owned_games_model */
        $player_owned_games_model = PlayerOwnedGamesModel::deserialize($response_model->response);
        return $player_owned_games_model;
    }


    /**
     * @param string $api_key
     * @param string $steam_id
     * @throws BadGatewayException
     * @throws GeneralSteamException
     * @throws TooManyRequestException
     * @return float|int
     * @author necipallef <necipallef@gmail.com>
     */
    public static function getPlayerCSGOTotalPlayTimeHours($api_key, $steam_id)
    {
        $player_owned_games_model = self::getAllGames($api_key, $steam_id);
        if ($player_owned_games_model === null){
            return 0;
        }
        foreach ($player_owned_games_model->games as $game_model){
            if ($game_model->appid === intval(Inventory::GameID)){
                return $game_model->playtime_forever / 60;
            }
        }

        return 0;
    }
}