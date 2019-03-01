<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");
require_once("../StetasClass/StetasUpgrade.php");
require_once("../Skills/SkillContoroll.php");

//倍率ダメージ+PTに防御UP付与
class Kikyou extends SkillBase{
    private $damageup=0;//上昇倍率    
    private $defBuff=array();//防御ステータスID        
    //スキル名 上昇ダメージ倍率
    public function __construct($name,$ct,$stetasArray){
        $this->damageup=$stetasArray[0];
        $this->defBuff=$stetasArray[1];
        $this->skillname = $name;
        $this->skillCharge=(int)$ct;
    }
                            //技使用者ステータス　対象ステータス
    public function skillaction($actionplayer, $targetSt, $id, $eId){
        $this->damageup = (int)$this->damageup;
        //ダメージ計算
        $Basedamage =  $actionplayer[$id]->power*(1+PowerCol($actionplayer[$id]->getStetas()));//基礎ダメージ
        $this->damage = ($Basedamage + random_int(10,100))*$this->damageup;//倍率計算
        //ダメージを与える
        $targetSt[$eId] = GeinDamage($targetSt[$eId], $this->damage);

        $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
        $this->actionMes .=  $targetSt[$eId]->characterName."に".$this->damage."のダメージを与えた<br>";

        //PTに防御バフ付与
        $responsdata = GeinStetasPT($actionplayer, $this->defBuff);
        $actionplayer = $responsdata["updateSt"]; 
        $this->actionMes .="味方に{$responsdata['Stname']}を付与した<br>";
        
        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->damage,"actMes"=>$this->actionMes);//ステータス情報を返す
        return $resdata; 
    }
}

?>