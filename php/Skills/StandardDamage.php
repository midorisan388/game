<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");
require_once("../StetasClass\StetasUpgrade.php");
require_once("../Skills/SkillContoroll.php");

//倍率ダメージスキル
class StandardDamage extends SkillBase{//倍率ダメージのみ
    private $damageup=0;//上昇倍率            
    //スキル名 上昇ダメージ倍率
    public function __construct($name,$ct,$stetasArray){
        $this->argument=$stetasArray[0];
        $this->skillname = $name;
        $this->skillCharge=(int)$ct;

       //echo("倍率ダメージスキル:".$this->skillname."を装備<br>");
    }
                            //技使用者ステータス　対象ステータス
    public function skillaction($actionplayer, $targetSt, $id, $eId){
        $this->damageup = (int)$this->argument;
        //ダメージ計算
        $Basedamage =  $actionplayer[$id]->power*(1+PowerCol($actionplayer[$id]->getStetas()));//基礎ダメージ
        $this->damage = ($Basedamage + random_int(10,100))*$this->damageup -  $targetSt[$eId]->defense;//倍率計算
        
        //ダメージを当たれる
        $targetSt[$eId] = GeinDamage($targetSt[$eId], $this->damage);

       // $targetSt[$eId]->currentDamage += $this->damage;

        $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
        $this->actionMes .=  $targetSt[$eId]->characterName."に".$this->damage."のダメージを与えた<br>";

        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->damage,"actMes"=>$this->actionMes);//ステータス情報を返す
        return $resdata; 
    }
}

?>