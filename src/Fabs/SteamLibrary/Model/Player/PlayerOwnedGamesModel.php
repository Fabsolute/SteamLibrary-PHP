<?php


namespace Fabs\SteamLibrary\Model\Player;


use Fabs\Serialize\SerializableObject;

class PlayerOwnedGamesModel extends SerializableObject
{

    /** @var int */
    public $games_count = 0;
    /** @var OwnedGameModel[] */
    public $games = [];


    function __construct()
    {
        parent::__construct();

        $this->registerProperty('games', OwnedGameModel::class, true);
    }
}