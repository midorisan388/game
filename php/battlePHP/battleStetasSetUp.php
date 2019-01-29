<?php
// セッションにステータスの初期値をセット
session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("C:\MAMP\htdocs\serverside\php\battlePHP\BattleActorStetas.php");//ステータスクラス
require_once("../getcharacterlist.php");//csvからくキャラデータを読み込む処理ファイル

$csvpath ="../../datas/csv/CharactersStetas.csv";
//初期化
$partyStetas_Origine=array();//ステータスの初期値
$partySt=array();//更新用パーティステータス
$enemyAllStList=array();//敵ステータスの初期値
$enemyStetasList=array();//全敵ステータス
$enemystandSt =array();//更新用敵ステータス
$index=0;//配置ID

try{
/*----------------------------------初期値セット-----------------------------------------------*/
//パーティのキャラIDを読み込む :今は適当なIDで探す
$partymember_ids=array(1,12,3,25);
$party_csvData=array();
for($index=0;$index<4;$index++){
    $partyid=$partymember_ids[$index];
    //$partymember_idsをもとにデータ取得
   array_push( $partyStetas_Origine,CharacterDataSet($partyid,$csvpath));//キャラデータの初期値取得

   //同時に更新用ステータス連想配列格納
   $party_csvData[$index]=array(
    "name"=>$partyStetas_Origine[$index][1], //表示キャラ名
    "id"=>$index,//$partyStetas_Origine[$index][0],//マスタデータID=>スキルIDやらはここから取得

    "type"=>$partyStetas_Origine[$index][12],//タイプ
    "count"=>10,//スキルCT
    "dravegage"=>0,//奥義ゲージ(%)
    "HP"=>$partyStetas_Origine[$index][16],//基礎体力
    "damage"=>0,//受けているダメージ
    "pow"=>$partyStetas_Origine[$index][17],//基礎攻撃力
    "def"=>$partyStetas_Origine[$index][18],//基礎防御力
    "magicpow"=>$partyStetas_Origine[$index][19],//基礎魔力
    "mental"=>$partyStetas_Origine[$index][20],//基礎精神力
    "skillid"=>$partyStetas_Origine[$index][21]//スキルID
   );

   array_push($partySt, new BattleActor($party_csvData[$index]));

   //$index++;
}

//テストデータ
$enemyAllStList =array(
    "enemy0"=>array(
        "name"=>"ゴブリン", 
        "id"=>0,
        "type"=>0,
        "count"=>10,
        "dravegage"=>0,
        "HP"=>1000,
        "damage"=>0,//受けてるダメージ
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>100,
        "def"=>100,
        "magicpow"=>800,
        "mental"=>200,
        "skillid"=>1
    ),
    "enemy1"=>array(
        "name"=>"ウルフ", 
        "id"=>1,
        "type"=>3,
        "count"=>12,
        "dravegage"=>0,
        "HP"=>1200,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>250,
        "def"=>210,
        "magicpow"=>10,
        "mental"=>20,
        "skillid"=>1
    ),
    "enemy2"=>array(
        "name"=>"スノウゴブリン", 
        "id"=>2,
        "type"=>0,
        "count"=>10,
        "dravegage"=>0,
        "HP"=>1000,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>200,
        "def"=>180,
        "magicpow"=>80,
        "mental"=>20,
        "skillid"=>1
    ),
    "enemy3"=>array(
        "name"=>"ボブゴブリン", 
        "id"=>3,
        "type"=>0,
        "count"=>10,
        "dravegage"=>0,
        "HP"=>18000,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>1000,
        "def"=>280,
        "magicpow"=>180,
        "mental"=>50,
        "skillid"=>1
    ),
    "enemy4"=>array(
        "name"=>"メリー", 
        "id"=>4,
        "type"=>2,
        "count"=>10,
        "dravegage"=>0,
        "HP"=>3600,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>100,
        "def"=>780,
        "magicpow"=>600,
        "mental"=>540,
        "skillid"=>1
    ),
    "enemy5"=>array(
        "name"=>"サハギン", 
        "id"=>5,
        "type"=>2,
        "count"=>10,
        "dravegage"=>0,
        "HP"=>2700,
        "damage"=>0,
        
        /**ここらへんのはエネミーマスタデータから取得**/
        "pow"=>800,
        "def"=>30,
        "magicpow"=>200,
        "mental"=>180,
        "skillid"=>1
    ),
);

//敵ステータスをセット
for($index =0;$index<4;$index++){
    array_push($enemyStetasList,new BattleActor($enemyAllStList["enemy".$index]));
    if($index<4){
        array_push($enemystandSt,new BattleActor($enemyAllStList["enemy".$index]));
    }
}
/*-------------------------------------------------------------------------------------------*/
//初回なら値をセット
//if(!isset($_SESSION["patySt"])){//バトル中使っていくパーティステータス
    $_SESSION["partySt"]=$partySt;
//}

//if(!isset($_SESSION["enemyStMst"]) && !isset($_SESSION["enemySt"])){
    $_SESSION["enemyStMst"] = $enemyStetasList ;//全敵ステータスリスト
    $_SESSION["enemySt"]=$enemystandSt;//攻撃対象になる敵のステータスリスト(前衛4体)    
//}

}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>