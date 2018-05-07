<?php

namespace BattleRoyale\Commands;

use BattleRoyale\GameManager;
use BattleRoyale\Sessions\Creator;
use BattleRoyale\Utilities\Utils;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Create extends PluginCommand {


	public function __construct(GameManager $plugin){
		parent::__construct("battlecreator", $plugin);
		$this->setDescription("Comando para crear arenas");
	}

	public function getCommand(): string{
		return "/battlecreator";
	}

	public function execute(CommandSender $sender, $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED."Debes usar este comando solo en juego!");
			return;
		}
		if(!$sender->isOp()){
			$sender->sendMessage(TextFormat::RED."No tienes permiso para usar este comando!");
			return;
		}
		if(!is_null(Utils::getPlayer($sender->getName()))){
			$sender->sendMessage(TextFormat::RED."No puedes usar este comando mientras estas en juego!");
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage(TextFormat::RED."Debes intruducir el nombre de la partida primero...");
			return;
		}
		if(count($args) > 1){
			$sender->sendMessage(TextFormat::RED."Has intrucido muchos argumentos...");
			return;
		}
		if(is_numeric($args[0])){
			$sender->sendMessage(TextFormat::RED."Debes utilizar un nombre valido!");
			return;
		}
		if(array_key_exists($args[0], GameManager::getInstance()->arenas)){
			$sender->sendMessage(TextFormat::RED."Esta arena ya existe!");
			return;
		}
		GameManager::$creators[$sender->getName()] = new Creator($sender, $args[0]);
		$sender->sendMessage(TextFormat::GREEN."Te has unido al modo creador!");
	}

}