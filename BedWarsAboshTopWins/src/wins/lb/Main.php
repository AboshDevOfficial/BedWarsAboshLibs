<?php

namespace wins\lb;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\level\particle\FloatingTextParticle;

class Main extends PluginBase implements Listener{

	public function onEnable() : void{
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."stats");
		@mkdir($this->getDataFolder()."stats/levels");
		$this->saveResource("config.yml");
		$this->getLogger()->Info(C::GREEN . "BedWarsTopWin Enabled!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $event) : void{
		$wconfig = new Config($this->getDataFolder()."stats/levels/Levels.yml", Config::YAML, [$event->getPlayer()->getName() => 0]);
		$player = $event->getPlayer();
		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$kills = $wconfig->getAll();
		arsort($kills);
		$title = "§l§aLEADERBOARD" . "§r\n§eBedwars Wins§r" . "\n\n";
		$i = 0;
		foreach($kills as $playerName => $killCount){
			$i++;
			if($i < 11 && $killCount){
				switch($i){
					case 1:
						$place = C::GREEN . "#1";
						$y = $i / 4.125;
						break;
					case 2:
						$place = C::YELLOW . "#2";
						$y = $i / 4.125;
						break;
					case 3:
						$place = C::GOLD . "#3";
						$y = $i / 4.125;
						break;
					default:
						$place = C::RED . "#" . $i;
						$y = $i / 4.125;
						break;
				}
				$this->getServer()->getDefaultLevel()->addParticle(new FloatingTextParticle(new Vector3($config->get("LeaderBoards-X") + 0.5, $config->get("LeaderBoards-Y") + 0.5 - $y, $config->get("LeaderBoards-Z") + 0.5), $place." ".C::AQUA.$playerName.C::GRAY." - ".C::WHITE.$killCount), [$player]);
			}
 		}
		$this->getServer()->getDefaultLevel()->addParticle(new FloatingTextParticle(new Vector3($config->get("LeaderBoards-X") + 0.5, $config->get("LeaderBoards-Y") + 0.75, $config->get("LeaderBoards-Z") + 0.5), $title), [$player]);
	}
	public function onRespawn(PlayerRespawnEvent $event) : void{
		$wconfig = new Config($this->getDataFolder()."stats/levels/Levels.yml", Config::YAML, [$event->getPlayer()->getName() => 0]);
		$player = $event->getPlayer();
		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$kills = $wconfig->getAll();
		arsort($kills);
		$title = "§l§aLEADERBOARD" . "§r\n§eBedwars Win§r" . "\n\n";
		$i = 0;
		foreach($kills as $playerName => $killCount){
			$i++;
			if($i < 11 && $killCount){
				switch($i){
					case 1:
						$place = C::GREEN . "#1";
						$y = $i / 4.125;
						break;
					case 2:
						$place = C::YELLOW . "#2";
						$y = $i / 4.125;
						break;
					case 3:
						$place = C::GOLD . "#3";
						$y = $i / 4.125;
						break;
					default:
						$place = C::RED . "#" . $i;
						$y = $i / 4.125;
						break;
				}
				$this->getServer()->getDefaultLevel()->addParticle(new FloatingTextParticle(new Vector3($config->get("LeaderBoards-X") + 0.5, $config->get("LeaderBoards-Y") + 0.5 - $y, $config->get("LeaderBoards-Z") + 0.5), $place." ".C::AQUA.$playerName.C::GRAY." - ".C::WHITE.$killCount), [$player]);
			}
 		}
		$this->getServer()->getDefaultLevel()->addParticle(new FloatingTextParticle(new Vector3($config->get("LeaderBoards-X") + 0.5, $config->get("LeaderBoards-Y") + 0.75, $config->get("LeaderBoards-Z") + 0.5), $title), [$player]);
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		$lbcfg = new Config($this->getDataFolder()."config.yml", Config::YAML);
		if($cmd->getName() == "setbww"){
			if($sender instanceof Player){
				$lbcfg->set("LeaderBoards-X", $sender->getFloorX());
				$lbcfg->set("LeaderBoards-Y", $sender->getFloorY());
				$lbcfg->set("LeaderBoards-Z", $sender->getFloorZ());
				$lbcfg->save();
				$sender->sendMessage(C::GREEN."LeaderBoards spawn coordinates set in your location, please re-login...");
             }
            return true;
            }
             if ($sender instanceof ConsoleCommandSender) {
				$sender->sendMessage(C::YELLOW."Please use this command in-game!");
			}
		return true;
		}

	public function addWin(Player $player) : void{
		$wconfig = new Config($this->getDataFolder()."stats/levels/Levels.yml", Config::YAML);
		$wconfig->set($player->getName(), $wconfig->get($player->getName()) + 1);
		$wconfig->save();
	}
}
