<?php
//リズムボタンがタップされたときに呼び出されるスキル処理
ini_set('display_errors',"On");
error_reporting(E_ALL);
if(!class_exists("BattleActor")){
    require_once("./BattleActorStetas.php");//バトルキャラクターステータスクラス
}

session_start();

require_once("../NotesCounter.php");//ノーツのタイミング計算処理

$enemyStetasList = array();//敵ステータスリスト
$enemyStetas =  array();//攻撃対象になる敵のステータス
$partyStetas = array();//バトルメンバーステータス
$gameOver = "Continue";//HPの状態によりゲーム続行フラグ変更

//前提条件　　ステータスの初期化が完了している
$enemyStetasList = $_SESSION["enemyStMst"];//敵ステータスリスト
$enemyStetas = $_SESSION["enemySt"];//攻撃対象になる敵のステータス
$partyStetas = $_SESSION["partySt"];//バトルメンバーステータス

$csvpath ="../../datas/csv/CharactersStetas.csv";


try{
    $notesdata = $_POST["notesdata"];
    $gametimer = $_POST["time"];
    $memberid = (int)$_POST["lernid"];
    $characterid = (int)($partyStetas[$memberid]->characterId);

    $NotesStetas = NotesCol($gametimer,$notesdata,$memberid);

    if($NotesStetas["notesdatas"]["judge"] !== "ALWAY" && $NotesStetas["notesdatas"]["judge"] !== "MISS" ){//プレイヤーの攻撃判定
        if(isset($partyStetas[$memberid])){
            if((int)$partyStetas[$memberid]->HP > (int)$partyStetas[$memberid]->curretnDamage){//行動可能時
                //$charaSt = CharacterDataSet($characterid,$csvpath);
                $Basedamage=$partyStetas[$memberid]->power;//(int)$partyStetas["member".$memberid]["pow"];//攻撃力
                $damage = $Basedamage+random_int(15,20);

                //体力参照,更新
                $totaldamage = (int)$enemyStetas[$memberid]->curretnDamage + $damage; //(int)$enemyStetas[$memberid]["damage"] + $damage;//受けているダメージの加算処理
                //$enemyStetas[$memberid]["damage"] = $totaldamage;
                $enemyStetas[$memberid]->curretnDamage = $totaldamage;
            }else{
                //メンバーが戦闘不能時の処理
                (int)$partyStetas[$memberid]->curretnDamage -=100;
                $damage= $partyStetas[$memberid]->name."は体力を100回復した";
            }
        }else{
            //メンバーが編成されていない処理
            $damage=0;
        }
    }else{//エネミーの攻撃判定
        if(isset($enemyStetas[$memberid])){
            if((int)$enemyStetas[$memberid]["HP"] > 0){
                $Basedamage=(int)$enemyStetas[$memberid]->power;//(int)$enemyStetas[$memberid]["Pow"];//攻撃力

                $damage = $Basedamage+random_int(15,20);

                //DBの体力参照,更新
                $totaldamage = (int)$partyStetas[$memberid]->curretnDamage + $damage;
                $partyStetas[$memberid]->curretnDamage = $totaldamage;
            }
        }else{
            //エネミーデータが存在しないときの処理
            $damage=0;
        }
}

//生存判定
for($i=0;$i<4;$i++){
    if((int)$partyStetas[$memberid]->curretnDamage < (int)$partyStetas[$memberid]->HP){
        $gameOver = "Continue";
        break;
    }else{
        $gameOver = "Gameover";
    }
}
for($i=0;$i<4;$i++){
    if((int)$enemyStetas[$memberid]->curretnDamage < (int)$enemyStetas[$memberid]->HP){
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

header('Content-Type: application/json; charset=utf-8');
$resjson =json_encode( $resdata,JSON_PRETTY_PRINT );
echo  $resjson;
    
}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>
