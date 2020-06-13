<?php

namespace sandbag_system\pmmp\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use sandbag_system\pmmp\entities\SandbagEntity;
use sandbag_system\pmmp\items\SandbagRemoveItem;
use slot_menu_system\models\SlotMenuElement;
use slot_menu_system\pmmp\items\SlotMenuElementItem;

class SandbagCommand extends Command
{
    public function __construct() {
        parent::__construct("sandbag", "", "");
        $this->setPermission("Sandbag.Command");
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            $sender->getInventory()->addItem(new SlotMenuElementItem(new SlotMenuElement(ItemIds::EMERALD, "サンドバッグ", 0, function (Player $player) {
                $nbt = new CompoundTag('', [
                    'Pos' => new ListTag('Pos', [
                        new DoubleTag('', $player->getX()),
                        new DoubleTag('', $player->getY()),
                        new DoubleTag('', $player->getZ())
                    ]),
                    'Motion' => new ListTag('Motion', [
                        new DoubleTag('', 0),
                        new DoubleTag('', 0),
                        new DoubleTag('', 0)
                    ]),
                    'Rotation' => new ListTag('Rotation', [
                        new FloatTag("", $player->getYaw()),
                        new FloatTag("", 0)
                    ]),
                ]);
                $sandbag = new SandbagEntity($player->getLevel(), $nbt);
                $sandbag->spawnToAll();
            }),ItemIds::EMERALD));
            $sender->getInventory()->addItem(new SandbagRemoveItem());
        }
    }
}