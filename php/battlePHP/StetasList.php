<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("./StetasClass.php");//クラスリスト読み込み

//ステータスのリスト
$Buffes =array( //タイプクラス(バフ表示名,効果量,効果時間)
    "atkup"=> new PowerUp_Player("攻撃力UP",0.1,5,"Damage"),
    "defup"=> new DefenseUp_Player("防御力UP",0.1,5,"toDamage"),
    "driveup"=> new PowerUp_Player("奥義ゲージ力UP",0.1,1,"StetasSet"),
    "Heal"=> new DefenseUp_Player("HP回復",0.1,5,"Damage")
);

$total =0;
$damage =100;
$i=0;

$stetas=array();
$up = new PowerUp_Player("攻撃力UP(大)",0.35,1,"Damage");
$up1 = new PowerUp_Player("攻撃力UP(中)",0.15,3,"Damage");
$up2=new DefenseUp_Player("防御力UP(大)",0.50,1,"toDamage");
array_push($stetas, $up,$up1,$up2);


//クラスの選別
foreach($stetas as  $st){
    if(get_class($st) === "PowerUp"){
        $total +=  $st->upstetas;
        $st->Buffturn -=1;
        if($st->Buffturn === 0){
            array_splice($stetas,$i,1);
        }
    }
    $i++;
}
$damage *=(1+$total);
echo  "ダメージ計算後".$damage."<br>";

$_SESSION["stetas"] = $stetas;
var_dump($_SESSION["stetas"]);
?>