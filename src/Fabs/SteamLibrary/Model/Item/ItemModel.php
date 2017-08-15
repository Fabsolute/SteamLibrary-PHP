<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 16/04/2017
 * Time: 11:36
 */

namespace Fabs\SteamLibrary\Model\Item;


class ItemModel extends SteamAssetModel
{
    /** @var SteamDescriptionModel */
    public $description;
    /** @var string */
    public $type;
    /** @var string */
    public $exterior = null;
    /** @var SteamStickerModel[] */
    public $stickers = [];
    /** @var string */
    public $inspect_in_game_link;
    /** @var string */
    public $name_tag = null;
    /** @var string */
    public $rarity_name = null;
    /** @var string */
    public $rarity_color = null;

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('description', SteamDescriptionModel::class);
        $this->registerProperty('stickers', SteamStickerModel::class, true);
    }
}