<?php

namespace CustomKnockback;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    public function onLoad(){
        $this->getLogger()->info(TextFormat::GREEN . "CustomKnockback plugin loading...");
    }

    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "CustomKnockback plugin enabled!");
    }

    public function onDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        $level = $entity->getLevel()->getName();
        $worldConfig = new Config($this->getDataFolder() . "worlds.yml", Config::YAML);
        if($worldConfig->exists($level)){
            $config = $worldConfig->get($level);
            $factor = $config["knockback-factor"];
            $delay = $config["knockback-delay"];
            $motion = $config["knockback-motion"];
            $leaching = $config["knockback-leaching"];
            $height = $config["knockback-height"];

            $event->setKnockBack($event->getKnockBack() * $factor);
            $event->setAttackTime($delay);
            $event->setMotion($motion);
            $event->setLeach($leaching);
            $event->setHitHeight($height);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        switch($command->getName()){
            case "customknockback":
                if(!isset($args[0])){
                    $sender->sendMessage(TextFormat::RED . "Usage: /customknockback <setkb|setdelay|setmotion|setleaching|setheight> <value>");
                    return false;
                }
                switch($args[0]){
                    case "setkb":
                        if(!isset($args[1])){
                            $sender->sendMessage(TextFormat::RED . "Usage: /customknockback setkb <value>");
                            return false;
                        }
                        $this->getConfig()->set("default", [
                            "knockback-factor" => (float) $args[1]
                        ]);
                        $this->getConfig()->save();
                        $sender->sendMessage(TextFormat::GREEN . "Default knockback factor set to " . $args[1]);
                        break;
                    case "setdelay":
                        if(!isset($args[1])){
                            $sender->sendMessage(TextFormat::RED . "Usage: /customknockback setdelay <value>");
                            return false;
                        }
                        $this->getConfig()->set("default", [
                            "knockback-delay" => (int) $args[1]
                        ]);
                        $this->getConfig()->save();
                        $sender->sendMessage(TextFormat::GREEN . "Default knockback delay set to " . $args[1]);
                        break;
                    case "setmotion":
                        if(!isset($args[1])){
                            $sender->sendMessage(TextFormat::RED . "Usage: /customknockback setmotion <value>");
                            return false;
                        }
                        $this->getConfig()->set("default", [
                            "knockback-motion" => (float) $args[1]
                        ]);
                        $this->getConfig()->save();
                        $sender->sendMessage(TextFormat::GREEN . "Default knockback motion set to " . $args[1]);
                        break;
                    case "setleaching":
                        if(!isset($args[1])){
                            $sender->sendMessage(TextFormat::RED . "Usage: /customknockback setleaching <value>");
                            return false;
                        }
                        $this->getConfig()->set("default", [
                            "knockback-leaching" => (float) $args[1]
                        ]);
                        $this->getConfig()->save();
                        $sender->sendMessage(TextFormat::GREEN . "Default knockback leaching set to " . $args[1]);
                        break;
                    case "setheight":
                        if(!isset($args[1])){
                            $sender->sendMessage(TextFormat::RED . "Usage: /customknockback setheight <value>");
                            return false;
                        }
                        $this->getConfig()->set("default", [
                            "knockback-height" => (float) $args[1]
                        ]);
                        $this->getConfig()->save();
                        $sender->sendMessage(TextFormat::GREEN . "Default knockback height set to " . $args[1]);
                        break;
                    default:
                        $sender->sendMessage(TextFormat::RED . "Usage: /customknockback <setkb|setdelay|setmotion|setleaching|setheight> <value>");
                        return false;
                        break;
                }
                break;
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info(TextFormat::RED . "CustomKnockback plugin disabled.");
    }
}
