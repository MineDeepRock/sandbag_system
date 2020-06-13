<?php

namespace sandbag_system\pmmp\entities;

use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\UUID;

class SandbagEntity extends Human
{
    const NAME = "Sandbag";

    public $geometryId = "geometry." . self::NAME;
    public $geometryName = self::NAME . ".geo.json";

    protected $skinId = "Standard_CustomSlim";
    protected $skinName = self::NAME;

    protected $capeData = "";

    public $width = 0.6;
    public $height = 1.8;
    public $eyeHeight = 1.5;

    protected $gravity = 0.08;
    protected $drag = 0.02;

    public $scale = 1.0;

    public $defaultHP = 20;
    public $uuid;

    private $spawnLevel;

    public function __construct(Level $level, CompoundTag $nbt) {
        $this->spawnLevel = $level;
        $this->uuid = UUID::fromRandom();
        $this->initSkin();

        parent::__construct($level, $nbt);
        $this->setRotation($this->yaw, $this->pitch);
        $this->setNameTagAlwaysVisible(true);
        $this->sendSkin();
    }

    public function initEntity(): void {
        parent::initEntity();
        $this->setScale($this->scale);
        $this->setMaxHealth($this->defaultHP);
        $this->setHealth($this->getMaxHealth());
    }

    private function initSkin(): void {
        $this->setSkin(new Skin(
            $this->skinId,
            file_get_contents("./plugin_data/SandbagSystem/" . $this->skinName . ".skin"),
            $this->capeData,
            $this->geometryId,
            file_get_contents("./plugin_data/SandbagSystem/" . $this->geometryName)
        ));
    }

    /**
     * @return Level
     */
    public function getSpawnLevel(): Level {
        return $this->spawnLevel;
    }
}