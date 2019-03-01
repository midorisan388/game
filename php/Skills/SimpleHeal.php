<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

class SimpleHeal extends SkillBase{//単体回復
    private $heal = 1;//回復量

    public function __construct($name,$ct,$stetasArray){
       $this->skillname=$name;
       $this->argument = $stetasArray[0];
       $this->skillCharge=(int)$ct;
    }

    public function skillaction($actionplayer, $targetSt, $id, $eId){
        $this->heal = (int)$this->argument;
        
           for($i =0 ;$i<4; $i++){
              $actionplayer[$i]->currentDamage -= $this->heal;
              if( $actionplayer[$i]->currentDamage < 0)  $actionplayer[$i]->currentDamage=0;
           }
          
        $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
        $this->actionMes .=  "味方を".$this->heal."回復した<br>";

        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->heal,"actMes"=>$this->actionMes);//ステータス情報を返す
        return $resdata; 
    }
}

?>