<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 08/04/2017
 * Time: 18:48
 */

namespace Fabs\SteamLibrary\Model\Item;

use Fabs\Serialize\SerializableObject;

class SteamDescriptionTagModel extends SerializableObject
{
    /** @var string */
    public $category = null;
    /** @var string */
    public $internal_name = null;
    /** @var string */
    public $localized_category_name = null;
    /** @var string */
    public $localized_tag_name = null;
    /** @var string */
    public $color = null;
}