<?php
// セッションにステータスの初期値をセット
session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("../../datas/sql.php");//ユーザー情報テーブル情報
require_once("./BattleActorStetas.php");//ステータスクラス
require_once("../getcharacterlist.php");//csvからキャラデータを読み込む処理ファイル


function SetStetas($ids){
    $index=0;//配置ID
    $partySt=array();//更新用パーティステータス
    $partyStetas_Origine=array();//ステータスの初期値
    $party_csvData=array();
    $csvpath ="../../datas/csv/CharactersStetas.csv";

    //パーティのキャラIDを読み込む
    for($index=0;$index<4;$index++){
        $partyid= (int)$ids[$index];
        if($partyid >= 0){
            //$partymember_idsをもとにデータ取得
            array_push($partyStetas_Origine,getRecord($partyid,$csvpath));//キャラデータの初期値取得(マスターデータ)

            //同時に更新用ステータス連想配列格納
            $party_csvData[$index]=array(
                "name"=>$partyStetas_Origine[$index][2], //表示キャラ名
                "id"=>$partyid,//キャラクターID
                "imgid"=>$partyStetas_Origine[$index][1],//画像の通し番号
                "type"=>$partyStetas_Origine[$index][13],//バトルタイプ
                "dravegage"=>0,//奥義ゲージ(%)
                "HP"=>$partyStetas_Origine[$index][15],//基礎体力
                "pow"=>$partyStetas_Origine[$index][16],//基礎攻撃力
                "def"=>$partyStetas_Origine[$index][17],//基礎防御力
                "magicpow"=>$partyStetas_Origine[$index][18],//基礎魔力
                "mental"=>$partyStetas_Origine[$index][19],//基礎精神力
                "skillid"=>$partyStetas_Origine[$index][21]//スキルID
            );

            array_push($partySt, new BattleActor($party_csvData[$index]));
        }
    }

    return $partySt;
}

//初期化
$enemyAllStList=array();//敵ステータスの初期値
$enemyStetasList=array();//全敵ステータス
$enemystandSt =array();//更新用敵ステータス
$userid = $_SESSION["userid"];//ログインユーザーID
try{
/*----------------------------------初期値セット-----------------------------------------------*/
//SQL接続-----------------------------------------------------------------
$sql_list=new PDO("mysql:host=$SERV;dbname=$GAME_DBNAME",$USER,$PASSWORD);
$sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//----------------------------------------------------------------------
$user_party_table = $sql_list->query("SELECT * FROM {$userpartytable} WHERE UserID='{$userid}'");
$user_party_table=$user_party_table->fetch();
$partymember_ids=[$user_party_table[1],$user_party_table[2],$user_party_table[3],$user_party_table[4]];

//テストデータ
$enemyAllStList =array(
    "enemy0"=>array(
        "name"=>"ゴブリン", 
        "id"=>0,
        "imgid"=>0000,
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
        "imgid"=>0000,
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
        "imgid"=>0000,
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
        "imgid"=>0000,
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
        "imgid"=>0000,
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
        "imgid"=>0000,
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
    if($index<6){
        array_push($enemystandSt,new BattleActor($enemyAllStList["enemy".$index]));
    }
}
/*-------------------------------------------------------------------------------------------*/
//初回なら値をセット
//if(!isset($_SESSION["patySt"])){//バトル中使っていくパーティステータス
    $_SESSION["partySt"]=SetStetas($partymember_ids);
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