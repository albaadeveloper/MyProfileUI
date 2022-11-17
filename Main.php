<?php

namespace AlbaaDev\MyProfileUI;
// PocketMine Use
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

// Plugin use
use onebone\economyapi\EconomyAPI;
use AlbaaDev\MyProfileUI\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {
    
    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
        date_default_timezone_set("Asia/Jakarta");
    }
    
    public function oncommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        if($cmd->getName() == "myprofile"){
            $this->MyProfileUI($sender);
        }
        return true;
    }
    
    public function MyProfileUI($player){
        $form = new SimpleForm(function(Player $sender, int $data = null){
            if($data === null){
                $this->getConfig->get("Profile")["Button"]["Message"];
                return true;
            }
            if($data === 0){
                $this->getConfig()->get("Profile")["Button"]["Message"];
                return true;
            }
        });
        $content = str_replace (["{money}", "{player}", "{rank}", "{ping}", "{date}", "{online}"], [$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player), $player->getName(), $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player), $player->getNetworkSession()->getPing(), date("H:i | d/m/y"), count($this->getServer()->getOnlinePlayers())], $this->getConfig()->get("Profile")["SimpleForm"]["Content"]);
        $form->setTitle($this->getConfig()->get("Profile")["SimpleForm"]["Title"]);
        $form->setContent($content);
        $form->addButton($this->getConfig()->get("Profile")["Button"]["Name"], 0, $this->getConfig()->get("Profile")["Button"]["Image"]);
        $form->sendToPlayer($player);
        return $form;
    }
}