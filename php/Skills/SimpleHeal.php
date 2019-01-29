<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

class SimpleHeal extends SkillBase{//単体回復
    private $heal = 1;//回復量

    public function __construct($name,$stetasArray){
        $this->skillname=$name;
       $this->argument = $stetasArray[0];
       //echo("単体回復スキル:".$this->skillname."を装備<br>");
    }

    public function skillaction($actionplayer, $targrtSt){
        $this->heal = $this->argument;
           $actionplayer["damage"] -= $this->heal; 
            
            //echo $this->skillname." 発動！<br>";
            //echo $this->heal." 回復した<br>";

            $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
            return $resdata; 
    }
}

?>