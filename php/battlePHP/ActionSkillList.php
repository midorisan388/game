<?php
//リズムボタンがタップされたときに呼び出されるスキル処理
ini_set('display_errors',"On");
error_reporting(E_ALL);


if(!class_exists("BattleActer")){
    require_once("./BattleActorStetas.php");
}//バトルキャラクターステータスクラス

session_start();

require_once("../NotesCounter.php");//ノーツのタイミング計算処理

//生存フラグ
$party_safety=false;
$enemy_safety=false;

$enemyStetasList = array();//敵ステータスリスト
$enemyStetas =  array();//攻撃対象になる敵のステータス
$partyStetas = array();//バトルメンバーステータス
$gameFlag = "Continue";//HPの状態によりゲーム続行フラグ変更
$damage = "0";
$motion="idle";
$actionMes ="";//行動内容を表記

//前提条件　　ステータスの初期化が完了している
$enemyStetasList = $_SESSION["enemyStMst"];//敵ステータスリスト
$enemyStetas = $_SESSION["enemySt"];//攻撃対象になる敵のステータス
$partyStetas = $_SESSION["partySt"];//バトルメンバーステータス

$skillUpdate=array();//スキル使用時に更新されたステータス格納
$csvpath ="../../datas/csv/CharactersStetas.csv";

function skillLateFunc($skillDt, $id){ //スキル使用後処理
   
    global $partyStetas;
    global $enemyStetas;
   // global $damage;
    global $actionMes;
    global $motion;

    $partyStetas = $skillDt["acter"];//パーティステータス格納
    $enemyStetas = $skillDt["target"];//敵ステータス格納
    //$damage = $skillDt["damage"];//ダメージ量格納
    $actionMes = $skillDt["actMes"];//スキルメッセージ

    $partyStetas[$id]->skillCurrentCount =0;//スキルCTリセット
    $motion="skill";
    //セッションデータ更新
    $_SESSION["enemySt"]=$enemyStetas;//攻撃対象になる敵のステータス
    $_SESSION["partySt"]=$partyStetas;//バトルメンバーステータス

}

function stetasCheck($actioncharacter, $targetcharacter){//ダメージ計算
    //ステータス更新
    $Basedamage=$actioncharacter->power;
    $damage = $Basedamage+random_int(15,20) - $targetcharacter->defense;

    //体力参照,更新
    $totaldamage = $targetcharacter->currentDamage + $damage; 
    $targetcharacter->currentDamage = $totaldamage;

    //ステータスによる数値調整
    if($targetcharacter->currentDamage > $targetcharacter->HP){
        $targetcharacter->currentDamage=$targetcharacter->HP;
    }

    return $damage;
}

function skillUseFlag($actioncharacter){//スキル使用フラグを変返す
   if($actioncharacter->skillCharge <= $actioncharacter->skillCurrentCount)
    return true;
   else return false;
} 

function characterAction($actioncharacter){//生存フラグをかえす
    if($actioncharacter->HP > $actioncharacter->currentDamage){
        return true;
    }else return false;
}

function saftyParty($partyMembers){//パーティの生存キャラがいるか確認
    $saftyFlag=false;
    for($i=0;$i<4;$i++){//全メンバー生存しているか確認
        if(characterAction($partyMembers[$i]) && isset($partyMembers[$i])){
            $saftyFlag = true;
            break;
        }
    }
    return $saftyFlag;
}

function targetRandomSelect($targetmembers){ //ランダムにターゲット選択
    while(true){
        $selectId = rand(0,3);
        if(characterAction($targetmembers[$selectId]) && isset($targetmembers[$selectId])){
            break;
        }
    }
    return $selectId;
}

try{
    $notesdata = $_POST["notesdata"];
    $gametimer = $_POST["time"];
    $memberid = (int)$_POST["lernid"];
    $notesup = $_POST["updatenotes"];
   // $characterid = (int)($partyStetas[$memberid]->characterId);

    $NotesStetas = NotesCol($gametimer,$notesdata,$memberid,$notesup);
    
    if($NotesStetas["notesdatas"]["judge"] === "OVER"){  //敵が戦闘不能
        $actionMes = $partyStetas[$memberid]->characterName."ノーツ完走";
    }else if($NotesStetas["notesdatas"]["judge"] !== "ALWAY" && $NotesStetas["notesdatas"]["judge"] !== "MISS" ){//プレイヤーの攻撃判定
        if(isset($partyStetas[$memberid])){
            if(characterAction($partyStetas[$memberid])){//行動可能時
                if(saftyParty($enemyStetas)){//敵パーティが全滅していない
                    //ターゲット決定
                    if(characterAction($enemyStetas[$memberid])){//同レーンの敵が生存している
                        $enemyId = $memberid;
                    }else{
                        $enemyId = targetRandomSelect($enemyStetas);//相手が戦闘不能の時他のラインの敵を狙う
                    }

                    if(skillUseFlag($partyStetas[$memberid])){//スキル使用間隔にチャージできていればスキル使用
                        //スキル使用可能時
                        if(characterAction($enemyStetas[$memberid])){//同レーンのエネミーが生存中か
                            $enemyId = $memberid;
                        }else{
                            $enemyId = targetRandomSelect($enemyStetas);//相手が戦闘不能の時他のラインの敵を狙う
                        }
                        $skillUpdate = $partyStetas[$memberid]->skillUse($partyStetas, $enemyStetas, $memberid, $enemyId);//スキル使用後の更新ステータス受け取り
                        skillLateFunc($skillUpdate, $memberid); //スキル使用後処理

                        $partyStetas[$memberid]->skillCurrentCount=0;

                    }else{//通常行動
                    
                        $damage_val = stetasCheck($partyStetas[$memberid], $enemyStetas[$enemyId]);

                        $partyStetas[$memberid]->skillCurrentCount++;

                        $motion="attack";

                        $actionMes = $partyStetas[$memberid]->characterName."の攻撃<br>";
                        $actionMes .= $enemyStetas[$enemyId]->characterName."に<strong class=damage>".$damage_val."</strong>ダメージ与えた<br>";
                    }
                }else{//敵が全滅している
                    $actionMes = "敵は全滅している<br>".$NotesStetas["notesdatas"]["judge"];
                }
            }else{
                //メンバーが戦闘不能時の処理
                $motion="dead";
                (int)$partyStetas[$memberid]->currentDamage -=100;
                $actionMes= $partyStetas[$memberid]->characterName."は体力を100回復した";

                if(characterAction($partyStetas[$memberid])) $motion="idle";//蘇生
            }
        }else{
            //メンバーが編成されていない処理
            $damage=0;
        }
    }else if($NotesStetas["notesdatas"]["judge"] === "MISS"){//エネミーの攻撃判定
        if(isset($enemyStetas[$memberid])){
            if(characterAction($enemyStetas[$memberid])){
                if(characterAction($partyStetas[$memberid])){
                    $damage_val =stetasCheck($enemyStetas[$memberid], $partyStetas[$memberid]);
                    $motion="damage";

                    $actionMes = $enemyStetas[$memberid]->characterName."の攻撃<br>";
                    $actionMes .= $partyStetas[$memberid]->characterName."は".$damage_val."のダメージを受けた<br>";
                }else{
                    //パーティキャラクターが戦闘不能
                    $motion="dead";
                    $actionMes = $partyStetas[$memberid]->characterName."は戦闘不能状態です<br>";
                }
            }else{
                $motion="damage";
                $actionMes = $partyStetas[$memberid]->characterName."敵は戦闘不能<br>{$NotesStetas["notesdatas"]["judge"]}<br>";
            }
        }else{
            //エネミーデータが存在しないときの処理
            $damage_val=0;
        }
    }else{//判定範囲外にタップされた
        $actionMes="<span class=damage>判定外</span><br>";
    }

    /*-------------------生存判定----------------------------*/
    if(!characterAction($partyStetas[$memberid])){
        $motion="dead";
    }

    for($i=0;$i<4;$i++){
        if(characterAction($partyStetas[$i])){
            $party_safety=true;
            break;
        }
    }
    if($party_safety){
        $gameFlag = "Continue";
    }else{
        $gameFlag = "Gameover";
    }

    for($i=0;$i<4;$i++){
        if(characterAction($enemyStetas[$i])){
            $enemy_safety=true;
            break;
        }
    }
    if($enemy_safety){
        $gameFlag = "Continue";
    }else{
        $gameFlag = "Clear";
    }
    /*-----------------------------------------------*/

     //セッションデータ更新
     $_SESSION["enemyStMst"]=$enemyStetasList ;//敵ステータスリスト
     $_SESSION["enemySt"]=$enemyStetas ;//攻撃対象になる敵のステータス
     $_SESSION["partySt"]=$partyStetas ;//バトルメンバーステータス

    //送信用データJSON
    $resdata = array(
        "cID"=>$memberid,
        "noteshantei"=>$NotesStetas["hanteilist"],//スコア、判定カウント
        "notesdata"=>$_SESSION["notesdata"],//ノーツデータ
        "motionState"=>$motion,
        "gameOverFlag"=>$gameFlag,//ゲーム続行フラグ
        "enemydata"=>$enemyStetas,//敵ステータス
        "memberdata"=>$partyStetas,//パーティステータス
        "message"=>$actionMes//表記内容
    );

    //レスポンスデータ整頓
    header('Content-Type: application/json; charset=utf-8');
    $resjson =json_encode( $resdata,JSON_PRETTY_PRINT );
    echo  $resjson;
    

}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>
