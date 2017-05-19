<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 16/04/2017
 * Time: 11:36
 */

namespace Fabs\SteamLibrary\Model;


class SteamItemModel extends SteamAssetModel
{
    /** @var SteamDescriptionModel */
    public $description;

    public function __construct()
    {
        parent::__construct();
        $this->registerProperty('description', SteamDescriptionModel::class);
    }
}