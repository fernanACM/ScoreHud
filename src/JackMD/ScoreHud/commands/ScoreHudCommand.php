<?php

declare(strict_types = 1);

/**
 *     _____                    _   _           _
 *    /  ___|                  | | | |         | |
 *    \ `--.  ___ ___  _ __ ___| |_| |_   _  __| |
 *     `--. \/ __/ _ \| '__/ _ \  _  | | | |/ _` |
 *    /\__/ / (_| (_) | | |  __/ | | | |_| | (_| |
 *    \____/ \___\___/|_|  \___\_| |_/\__,_|\__,_|
 *
 * ScoreHud, a Scoreboard plugin for PocketMine-MP
 * Copyright (c) 2018 JackMD  < https://github.com/JackMD >
 *
 * Discord: JackMD#3717
 * Twitter: JackMTaylor_
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * ScoreHud is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace JackMD\ScoreHud\commands;

use JackMD\ScoreHud\libs\JackMD\ScoreFactory\ScoreFactory;
use JackMD\ScoreHud\ScoreHud;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwnedTrait;

class ScoreHudCommand extends Command implements PluginOwned{
	use PluginOwnedTrait;

	/**
	 * ScoreHudCommand constructor.
	 *
	 * @param ScoreHud $plugin
	 */
	public function __construct(ScoreHud $plugin){
		parent::__construct("scorehud");
		$this->setDescription("Shows ScoreHud Commands");
		$this->setUsage("/scorehud <on|off|about|help>");
		$this->setAliases(["sh"]);
		$this->setPermission("sh.command.sh");

		$this->owningPlugin = $plugin;
	}


	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		if(!$sender instanceof Player){
			$sender->sendMessage(ScoreHud::PREFIX . "§cYou can only use this command in-game.");

			return false;
		}
		if(!isset($args[0])){
			$sender->sendMessage(ScoreHud::PREFIX . "§cUsage: /scorehud <on|off|about|help>");

			return false;
		}
		switch($args[0]){
			case "about":
				$sender->sendMessage(ScoreHud::PREFIX . "§ev5.2 for PocketMine-MP 4");
				$sender->sendMessage("§eAuthor:§f JackMD - Ifera");
				$sender->sendMessage("§eFixed:§f fernanACM");
				$sender->sendMessage("§e------(Contact)------");
				$sender->sendMessage("§cYouTube:§f fernanACM");
				$sender->sendMessage("§eGithub:§f https://github.com/fernanACM");
				$sender->sendMessage("§3Discord:§f fernanACM#5078");
				break;

			case "on":
				if(isset($this->owningPlugin->disabledScoreHudPlayers[strtolower($sender->getName())])){
					unset($this->owningPlugin->disabledScoreHudPlayers[strtolower($sender->getName())]);
					$sender->sendMessage(ScoreHud::PREFIX . "§aSuccessfully enabled ScoreHud.");
				}else{
					$sender->sendMessage(ScoreHud::PREFIX . "§cScoreHud is already enabled for you.");
				}
				break;

			case "off":
				if(!isset($this->owningPlugin->disabledScoreHudPlayers[strtolower($sender->getName())])){
					ScoreFactory::removeScore($sender);

					$this->owningPlugin->disabledScoreHudPlayers[strtolower($sender->getName())] = 1;
					$sender->sendMessage(ScoreHud::PREFIX . "§cSuccessfully disabled ScoreHud.");
				}else{
					$sender->sendMessage(ScoreHud::PREFIX . "§aScoreHud is already disabled for you.");
				}
				break;

			case "help":
			default:
				$sender->sendMessage(ScoreHud::PREFIX . "§cUsage: /scorehud <on|off|about|help>");
				break;
		}

		return false;
	}
}
