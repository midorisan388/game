<?php
// セッションにステータスの初期値をセット
session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("../getcharacterlist.php");

$csvpath ="../../csv/CharactersStetas.csv";
//初期化
$partyStetas_Origine=array();
$partySt=array();
$enemystandStList=array();
$enemystandSt =array();
$index=0;

try{
//パーティのキャラIDを読み込む :今は適当なIDで探す
$partymember_ids=array(1,12,15,25);

foreach($partymember_ids as $partyid){
    //$partymember_idsをもとにデータ取得
   array_push( $partyStetas_Origine,CharacterDataSet($partyid,$csvpath));

   //同時にステータス連想配列格納
   $partySt["member".$index]=array(
    "name"=>$partyStetas_Origine[$index][1], //表示名
    "characterid"=>$partyStetas_Origine[$index][0],//マスタデータID=>スキルIDやらはここから取得

    "type"=>$partyStetas_Origine[$index][12],//タイプ
    "count"=>10,//スキルCT
    "dravegage"=>20,//奥義ゲージ
    "HP"=>$partyStetas_Origine[$index][16],//基礎体力
    "damage"=>0,//受けているダメージ
    "Pow"=>$partyStetas_Origine[$index][17],//基礎攻撃力
    "Def"=>$partyStetas_Origine[$index][18],//基礎防御力
    "magicpow"=>$partyStetas_Origine[$index][18],//基礎魔力
    "mental"=>$partyStetas_Origine[$index][18]//基礎精神力
   );

   $index++;
}

$enemystandStList =array(
    "enemy0"=>array(
        "name"=>"ゴブリン", 
        "enemyid"=>0,
        "type"=>"ATK",
        "count"=>10,
        "dravegage"=>0,
        "HP"=>1000,
        "damage"=>0,//受けてるダメージ
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>100,
        "Def"=>100,
        "magicpow"=>800,
        "mental"=>200
    ),
    "enemy1"=>array(
        "name"=>"ウルフ", 
        "enemyid"=>1,
        "type"=>"JAM",
        "count"=>12,
        "dravegage"=>0,
        "HP"=>1200,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>250,
        "Def"=>210,
        "magicpow"=>10,
        "mental"=>20
    ),
    "enemy2"=>array(
        "name"=>"スノウゴブリン", 
        "enemyid"=>3,
        "type"=>"ATK",
        "count"=>10,
        "dravegage"=>0,
        "HP"=>1000,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>200,
        "Def"=>180,
        "magicpow"=>80,
        "mental"=>20
    ),
    "enemy3"=>array(
        "name"=>"ボブゴブリン", 
        "enemyid"=>10,
        "type"=>"ATK",
        "count"=>10,
        "dravegage"=>0,
        "HP"=>18000,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>1000,
        "Def"=>280,
        "magicpow"=>180,
        "mental"=>50
    ),
    "enemy4"=>array(
        "name"=>"メリー", 
        "enemyid"=>9,
        "type"=>"ENH",
        "count"=>10,
        "dravegage"=>0,
        "HP"=>3600,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>100,
        "Def"=>780,
        "magicpow"=>600,
        "mental"=>540
    ),
    "enemy5"=>array(
        "name"=>"サハギン", 
        "enemyid"=>11,
        "type"=>"ENH",
        "count"=>10,
        "dravegage"=>0,
        "HP"=>2700,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "Pow"=>800,
        "Def"=>30,
        "magicpow"=>200,
        "mental"=>180
    ),
);

//最初の敵ステータスをセット
for($index =0;$index<4;$index++){
    array_push($enemystandSt,$enemystandStList["enemy".$index]);
}

$_SESSION["partySt"]=$partySt;//バトル中使っていく
$_SESSION["enemyStMst"] = $enemystandStList ;//敵ステータスリスト
$_SESSION["enemySt"]=$enemystandSt;//攻撃対象になる敵のステータス
//$partyLeader =$_SESSION["partyLeader"];//リーダーステータス

//$enemystandSt[0] = $enemystandStList["enemy4"];
$resdata=array(
    "enemySt"=>$_SESSION["enemySt"],
    "partySt"=>$_SESSION["partySt"]
);

//var_dump($resdata);

function StetasDataGet(){
    $stetasData = array(
        "enemySt"=>$_SESSION["enemySt"],
        "partySt"=>$_SESSION["partySt"]
    );

    return $stetasData;
}

}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>