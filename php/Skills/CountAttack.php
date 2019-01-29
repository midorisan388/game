<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

class CountAttack extends SkillBase{//連続ダメージ
    private $count = 3;//攻撃回数

    public function __construct($name,$stetasArray){
       $this->argument=$stetasArray[0];//攻撃回数
       $this->skillname = $name;

       //echo("回数スキル:".$this->skillname."を装備<br>");
    }

    public function skillaction($actionplayer, $targrtSt){
        $this->count=$this->argument;
        //ダメージ計算
        $i=1;
        while($i <= $this->count){
            $Basedamage = $actionplayer["Pow"] *(1+PowerUpCol($actionplayer["stetas"]));//基礎ダメージ
            $damage = ($Basedamage + random_int(10,100));
            
           // echo $this->skillname." 発動！<br>";
           // echo (int)$damage."ダメージを与えた<br>";
            $i++;
        }
        $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
        return $resdata; 
    }
}

?>