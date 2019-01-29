<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("C:\MAMP\htdocs\serverside\php\StetasClass\StClass.php");

//攻撃力上げるステータス
class PowerUp extends Stetas{
    public function stetasfunc(){
        echo "攻撃力上昇!:".($this->argument_val*100)."%UP<br>";
    }
};

?>