<?php

namespace Fabs\SteamLibrary\Model\Player;


use Fabs\Serialize\SerializableObject;

class PlayerProfileModel extends SerializableObject
{
    /** @var  string */
    public $steamid;
    /** @var  int */
    public $communityvisibilitystate;
    /** @var  int */
    public $profilestate;
    /** @var  string */
    public $personaname;
    /** @var  int */
    public $lastlogoff;
    /** @var  string */
    public $profileurl;
    /** @var  string */
    public $avatar;
    /** @var  string */
    public $avatarmedium;
    /** @var  string */
    public $avatarfull;
    /** @var  string */
    public $personastate;
    /** @var  string */
    public $realname;
    /** @var  string */
    public $primaryclanid;
    /** @var  int */
    public $timecreated;
    /** @var  int */
    public $personastateflags;
    /** @var  string */
    public $loccountrycode;
    /** @var  string */
    public $locstatecode;
    /** @var  int */
    public $loccityid;
}