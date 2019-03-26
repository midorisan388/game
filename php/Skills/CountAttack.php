<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");
require_once("../Skills/SkillContoroll.php");

class CountAttack extends SkillBase{//連続ダメージ

    public function __construct($name,$ct,$stetasArray){
       $this->argument=$stetasArray[0];//攻撃回数
       $this->skillname = $name;
       $this->skillCharge=(int)$ct;
    }

    public function skillaction($actionplayer, $targetSt, $id,  $eId){
        $count=(int)$this->argument;
        //ダメージ計算
        $i=1;
        while($i <= $count){
            $Basedamage = $actionplayer[$id]->power*(PowerCol($actionplayer[$id]->getStetas()));//基礎ダメージ
            $this->damage = ($Basedamage + random_int(10,100)) - $targetSt[$eId]->defense;
         
            $targetSt[$eId]=GeinDamage($targetSt[$eId], $this->damage);
            //$targetSt[$eId]->currentDamage += $this->damage;

            $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
            $this->actionMes .=  $targetSt[$eId]->characterName."に".$this->damage."のダメージを与えた<br>";
            $i++;
        }
        
        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->damage,"actMes"=>$this->actionMes);//ステータス情報を返す
        return $resdata; 
    }
}

?>