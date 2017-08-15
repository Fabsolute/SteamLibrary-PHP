<?php

namespace Fabs\SteamLibrary\Model\Player;


use Fabs\Serialize\SerializableObject;

class PlayerProfilesModel extends SerializableObject
{
    /** @var  PlayerProfileModel[] */
    public $players;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->registerProperty('players', PlayerProfileModel::class, true);
    }
}