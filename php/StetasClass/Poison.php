<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("C:\MAMP\htdocs\serverside\php\StetasClass\StClass.php");

//攻撃力上げるステータス
class Poison extends Stetas{
    public function stetasfunc(){
        echo "毒のダメージ!:".($this->argument_val)."<br>";
    }
};

?>