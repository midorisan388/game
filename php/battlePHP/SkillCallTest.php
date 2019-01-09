<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("./SkillList.php");//クラスリスト読み込み

$enemySt = array(
    "name"=>"エネミー",
    "skillName"=>"attack",
    "HP"=>1000,
    "Pow"=>100,
    "damage"=>100,
    "stetas"=>array(
        new PowerUp("攻撃力UP", 0.1,  5, "Buff", "Damage"),
        new DefenseUp("防御力UP",0.1, 5, "Buff","toDamage")
    )
);

$playerSt = array(
    "name"=>"プレイヤー",
    "skillName"=>"debuffset",
    "HP"=>1000,
    "Pow"=>100,
    "damage"=>100,
    "stetas"=>array(
        new PowerUp("攻撃力UP", 0.1, 5, "Buff", "Damage"),
        new DefenseUp("防御力UP",0.1, 5, "Buff","toDamage"),
         new PowerUp("奥義ゲージ上昇量UP", 0.1, 1,"Buff", "StetasSet"),
         new PowerUp("攻撃力UP(大)", 0.3, 1,"Buff", "Damage")
    )
);

echo $playerSt["name"]."のスキル:<br>";

if( $playerSt["skillName"] === "attack"){
    $skill_instance = new StandardDamageSkill("強攻撃",1.5);
}else if($playerSt["skillName"] === "double"){
    $skill_instance = new DoubleAttack("連撃",3);
}else if($playerSt["skillName"] === "targetheal"){
    $skill_instance = new SimpleHeal("ヒール",300);

}else if($playerSt["skillName"] === "debuffset"){
    $skill_instance = new DeBuffDamage("毒攻撃", 
     array(
        new PowerUp("攻撃力UP", 0.1, 5, "Buff", "Damage"),
        new DefenseUp("防御力UP", 0.1, 5, "Buff","toDamage"),
         new PowerUp("奥義ゲージ上昇量UP", 0.1, 1 ,"Buff", "StetasSet"),
         new PowerUp("攻撃力UP(大)", 0.3, 1, "Buff", "Damage")
     )
    );
    $result = $skill_instance->skillaction($playerSt, $enemySt);
    $playerSt = $result["acter"];
    $enemySt = $result["target"];
}

//バフデバフ更新
$i=0;

foreach($playerSt["stetas"] as $st){
    if($st->Buffturn <= 0){//計算時に効果制限時間がきていたら削除
        array_splice($playerSt["stetas"],$i,1);
    }
    $i++;
}

var_dump($playerSt);
echo "<br>";
var_dump($enemySt);
?>