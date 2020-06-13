<?php

namespace sandbag_system\pmmp\items;


use pocketmine\item\Item;

class SandbagRemoveItem extends Item
{
    public const ITEM_ID = Item::WOODEN_SWORD;

    public function __construct() {
        parent::__construct(self::ITEM_ID, 0, "サンドバッグを除去");
        $this->setCustomName($this->getName());
    }
}