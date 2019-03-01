<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

$a=0;
$b="aaaa<br>";

   Fnc();
function Fnc(){
    global $a,$b;
    $a2 = $a;
    echo  $a2;
    echo  $b."s";
}
?>