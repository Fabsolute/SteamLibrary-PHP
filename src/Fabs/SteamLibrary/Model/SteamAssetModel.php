<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:46
 */

namespace Fabs\SteamLibrary\Model;

use Fabs\Serialize\SerializableObject;

class SteamAssetModel extends SerializableObject
{
    /** @var string */
    public $appid = null;
    /** @var string */
    public $contextid = null;
    /** @var string */
    public $assetid = null;
    /** @var string */
    public $classid = null;
    /** @var string */
    public $instanceid = null;
    /** @var string */
    public $amount = null;
}