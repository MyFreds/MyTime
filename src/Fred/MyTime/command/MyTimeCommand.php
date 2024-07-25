<?php

declare(strict_types=1);

namespace Fred\MyTime\command;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;
use Fred\MyTime\Main;
use Fred\MyTime\command\subcommand\DayCommand;
use Fred\MyTime\command\subcommand\NoonCommand;
use Fred\MyTime\command\subcommand\SunsetCommand;
use Fred\MyTime\command\subcommand\NightCommand;
use Fred\MyTime\command\subcommand\MidnightCommand;
use Fred\MyTime\command\subcommand\ResetCommand;

class MyTimeCommand extends BaseCommand {
    
    private Main $plugin;
    
    public function __construct(Main $plugin, string $name, string $description = "", array $aliases = []) {
        $this->plugin = $plugin;
        parent::__construct($plugin, $name, $description, $aliases);
        $this->setPermission("mytime.command.mtime");
    }

    protected function prepare(): void {
        $this->registerSubCommand(new DayCommand($this->plugin));
        $this->registerSubCommand(new NoonCommand($this->plugin));
        $this->registerSubCommand(new SunsetCommand($this->plugin));
        $this->registerSubCommand(new NightCommand($this->plugin));
        $this->registerSubCommand(new MidnightCommand($this->plugin));
        $this->registerSubCommand(new ResetCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $this->sendUsage();
    }
}
