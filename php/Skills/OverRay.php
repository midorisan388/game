<?php 
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");
require_once("C:\MAMP\htdocs\serverside\php\StetasClass\StetasUpgrade.php");

//倍率ダメージ+PT回復スキル
class OverRay extends SkillBase{
    private $damageup=0;//ダメージ倍率
    private $heal=0;//回復量   
    //スキル名 上昇ダメージ倍率
    public function __construct($name,$ct,$stetasArray){
        $this->damageup=$stetasArray[0];
        $this->heal=$stetasArray[1];
        $this->skillname = $name;
        $this->skillCharge=(int)$ct;

       //echo("倍率ダメージスキル:".$this->skillname."を装備<br>");
    }
                            //技使用者ステータス　対象ステータス
    public function skillaction($actionplayer, $targetSt, $id, $eId){
        $this->damageup = (int)$this->damageup;//整数にキャスト
        $this->heal =(int)$this->heal;

        //ダメージ計算
        $Basedamage =  $actionplayer[$id]->power*(1+PowerCol($actionplayer[$id]->getStetas()));//基礎ダメージ
        $this->damage = ($Basedamage + random_int(10,100))*$this->damageup -  $targetSt[$eId]->defense;//倍率計算
        
        $targetSt[$eId]->currentDamage += $this->damage;//ダメージ数増加

        $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
        $this->actionMes .=  $targetSt[$eId]->characterName."に".$this->damage."のダメージを与えた<br>";
        //PT回復
        for($i =0 ;$i<4; $i++){
            $actionplayer[$i]->currentDamage -= $this->heal;
            if( $actionplayer[$i]->currentDamage < 0)  $actionplayer[$i]->currentDamage=0;
         }
         $this->actionMes .= "味方を".$this->heal."回復した<br>";

        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->damage,"actMes"=>$this->actionMes);//ステータス情報を返す
        return $resdata; 
    }
}

?>