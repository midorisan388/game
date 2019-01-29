<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

//倍率ダメージスキル
class StandardDamage extends SkillBase{//倍率ダメージのみ
    private $damageup=0;//上昇倍率            
    //スキル名 上昇ダメージ倍率
    public function __construct($name,$updamage){
        $this->argument=$updamage;
       $this->skillname = $name;

       //echo("倍率ダメージスキル:".$this->skillname."を装備<br>");
    }
                            //技使用者ステータス　対象ステータス
    public function skillaction($actionplayer, $targrtSt){
        $this->damageup = $this->argument;
        //ダメージ計算
        $Basedamage = $actionplayer["Pow"] *(1+PowerUpCol($actionplayer["stetas"]));//基礎ダメージ
        $damage = ($Basedamage + random_int(10,100))*$this->damageup;//倍率計算
        
        //echo $this->skillname." 発動！<br>";
        //echo (int)$damage."ダメージを与えた<br>";

        $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
        return $resdata; 
    }
}

?>