<?php

declare(strict_types=1);

namespace Fred\MyTime\command\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

use CortexPE\Commando\BaseSubCommand;
use Fred\MyTime\Main;

class ResetCommand extends BaseSubCommand {
    
    private Main $plugin;

    public function __construct(Main $plugin, string $name = "reset", string $description = "Reset your personal time", array $aliases = []) {
        $this->plugin = $plugin;
        parent::__construct($name, $description, $aliases);
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

        $this->plugin->clearPlayerTime($sender);
    }
}
