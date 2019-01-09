<?php
session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("../getcharacterlist.php");
require_once("../NotesCounter.php");

$gameOver = "Continue";
$enemyStetasList = $_SESSION["enemyStMst"];//敵ステータスリスト
$enemyStetas = $_SESSION["enemySt"];//攻撃対象になる敵のステータス
$partyStetas = $_SESSION["partySt"];//バトルメンバーステータス
//$partyLeader =$_SESSION["partyLeader"];//リーダーステータス

$csvpath ="../../csv/CharactersStetas.csv";

try{
    $notesdata = $_POST["notesdata"];
    $gametimer = $_POST["time"];
    $memberid =(int)$_POST["lernid"];
    $characterid = (int)$partyStetas["member".$memberid]["characterid"];

    $NotesStetas = NotesCol($gametimer,$notesdata,$memberid);

    if($NotesStetas["notesdatas"]["judge"] !== ""){//プレイヤーの攻撃判定
        $charaSt = CharacterDataSet($characterid,$csvpath);

        if($charaSt){
            $Basedamage=(int)$partyStetas["member".$memberid]["Pow"];//攻撃力

            $damage = $Basedamage+random_int(15,20);

            //DBの体力参照,更新
            $totaldamage = (int)$enemyStetas[$memberid]["damage"] + $damage;
            $enemyStetas[$memberid]["damage"] = $totaldamage;

        } else{
            //echo "データがありません";
        }
    }else{//エネミーの攻撃判定
        if((int)$enemyStetas[$memberid]["HP"] > 0){
            $Basedamage=(int)$enemyStetas[$memberid]["Pow"];//攻撃力

            $damage = $Basedamage+random_int(15,20);

            //DBの体力参照,更新
            $totaldamage = (int)$partyStetas["member".$memberid]["damage"] + $damage;
            $partyStetas[$memberid]["damage"] = $totaldamage;
    }
}
//生存判定
for($i=0;$i<4;$i++){
    if((int)$partyStetas["member".$memberid]["damage"] < (int)$partyStetas["member".$memberid]["HP"]){
        $gameOver = "Continue";
        break;
    }else{
        $gameOver = "Gameover";
    }
}
for($i=0;$i<4;$i++){
    if((int)$enemyStetas[$memberid]["damage"] < (int)$enemyStetas[$memberid]["HP"]){
        $gameOver = "Continue";
        //ステータス入れ替え操作
        break;
    }else{
        //敵全滅で抜けたらフラグ
        $gameOver = "Clear";
    }
}


$_SESSION["enemyStMst"]=$enemyStetasList ;//敵ステータスリスト
$_SESSION["enemySt"]=$enemyStetas ;//攻撃対象になる敵のステータス
$_SESSION["partySt"]=$partyStetas ;//バトルメンバーステータス

//送信用データJSON
$resdata = array(
    "noteshantei"=>$NotesStetas["hanteilist"],
    "notesdata"=>$_SESSION["notesdata"],
    "gameOverFlag"=>$gameOver,
    "enemydata"=>$enemyStetas,
    "memberdata"=>$partyStetas,
    "damage"=>$damage
);

$resjson =json_encode( $resdata,JSON_PRETTY_PRINT );
echo  $resjson;
    
}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>
