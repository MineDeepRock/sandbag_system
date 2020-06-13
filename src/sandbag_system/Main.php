<?php

namespace sandbag_system;

use mine_deep_rock\pmmp\commands\NPCCommand;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use sandbag_system\pmmp\commands\SandbagCommand;
use sandbag_system\pmmp\entities\SandbagEntity;
use sandbag_system\pmmp\items\SandbagRemoveItem;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("sandbag", new SandbagCommand());
    }

    public function onDamaged(EntityDamageByEntityEvent $event) {
        $attacker = $event->getDamager();
        $victim = $event->getEntity();

        if ($victim instanceof SandbagEntity || $attacker instanceof Player) {
            $item = $attacker->getInventory()->getItemInHand();
            if ($item instanceof SandbagRemoveItem) {
                $victim->setInvisible(true);
                $victim->kill();
            }
        }
    }

    public function onEntityDeath(EntityDeathEvent $event) {
        $entity = $event->getEntity();
        if ($entity instanceof SandbagEntity) {
            if (!$entity->isInvisible()) {
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $i) use ($entity) :void {
                    $nbt = new CompoundTag('', [
                        'Pos' => new ListTag('Pos', [
                            new DoubleTag('', $entity->getX()),
                            new DoubleTag('', $entity->getY()),
                            new DoubleTag('', $entity->getZ())
                        ]),
                        'Motion' => new ListTag('Motion', [
                            new DoubleTag('', 0),
                            new DoubleTag('', 0),
                            new DoubleTag('', 0)
                        ]),
                        'Rotation' => new ListTag('Rotation', [
                            new FloatTag("", $entity->getYaw()),
                            new FloatTag("", 0)
                        ]),
                    ]);
                    $sandbag = new SandbagEntity($entity->getSpawnLevel(), $nbt);
                    $sandbag->spawnToAll();
                }), 20 * 2);
            }
        }
    }
}