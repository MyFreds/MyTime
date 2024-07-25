<?php

declare(strict_types=1);

namespace Fred\MyTime\command\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

use CortexPE\Commando\BaseSubCommand;
use Fred\MyTime\Main;

class DayCommand extends BaseSubCommand {
    
    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        parent::__construct("day", "Set your personal time to day", []);
        $this->setPermission("mytime.command.mtime");
    }

    protected function prepare(): void {
        // No arguments needed
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(C::RED . "This command can only be used in-game.");
            return;
        }

        $this->plugin->setPlayerTime($sender, 0);
        $sender->sendMessage(C::GREEN . "Your personal time has been set to" . C::YELLOW . " day.");
    }
}
