<?php

declare(strict_types=1);

namespace Fred\MyTime;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use CortexPE\Commando\PacketHooker;
use Fred\MyTime\command\MyTimeCommand;
use muqsit\simplepackethandler\SimplePacketHandler;

class Main extends PluginBase implements Listener {

    private array $players = [];

    public function onEnable(): void {
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
        
        $this->getServer()->getCommandMap()->register("mtime", new MyTimeCommand($this, "mtime", "Change personal time"));
        
        $packetMonitor = SimplePacketHandler::createInterceptor($this);
        $packetMonitor->interceptOutgoing(function (SetTimePacket $packet, NetworkSession $session): bool {
            $player = $session->getPlayer();
            if ($player !== null && isset($this->players[$player->getId()]) && $packet->time !== $this->players[$player->getId()]) {
                return false;
            }
            return true;
        });

        $this->getServer()->getPluginManager()->registerEvent(PlayerQuitEvent::class, function (PlayerQuitEvent $event): void {
            unset($this->players[$event->getPlayer()->getId()]);
        }, EventPriority::NORMAL, $this);
    }

    public function setPlayerTime(Player $player, int $time): void {
        $this->players[$player->getId()] = $time;
        $player->getNetworkSession()->sendDataPacket(SetTimePacket::create($time));
    }

    public function clearPlayerTime(Player $player): void {
        unset($this->players[$player->getId()]);
        $player->getNetworkSession()->sendDataPacket(SetTimePacket::create($player->getWorld()->getTime()));
        $player->sendMessage("§bYour personal time has been reset to server time.");
    }
}
