<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("C:\MAMP\htdocs\serverside\php\StetasClass\StClass.php");

    //防御力上げるステータス
    class DefenseUp extends Stetas{

        public function stetasfunc(){
            echo  "防御力上昇!:".$this->argument_val;
            /*totaldef += $upDef
                stetas["Def"] *= totaldef
            */
        }
    };
?>