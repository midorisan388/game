<?php
//バトル終了後の処理
ini_set('display_errors',"On");
error_reporting(E_ALL);

session_start();

// バトルで使用したセッションデータ削除

$clear_flag = $_GET["clearmode"];

//クリアフラグ更新
//SQL接続-----------------------------------------------------------------
require_once("../../datas/sql.php");
$sql_list=new PDO("mysql:host=$SERV;dbname=$GAME_DBNAME",$USER,$PASSWORD);
$sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//----------------------------------------------------------------------
$questId =$_SESSION["questId"] ;

$user_clear_data = $sql_list->query("SELECT * FROM {$userquestclearflag} WHERE UserID = '{$_SESSION["userid"]}' AND QuestID='{$questId}'");
if($clear_flag === "clear"){
    $sql_list->query("UPDATE {$userquestclearflag} SET ClearFlag=1 WHERE UserID = '{$_SESSION["userid"]}' AND QuestID='{$questId}'");//クリアにする
    $sql_list->query("UPDATE {$userquestclearflag} SET ClearDate=Now() WHERE UserID = '{$_SESSION["userid"]}' AND QuestID='{$questId}'");//クリアにする
}
if(!$_SESSION["AP_UPDATE"]){
    $_SESSION["USER_DATA"]["userAP"] -= 10;
    if($_SESSION["USER_DATA"]["userAP"] <= 0){
        $_SESSION["USER_DATA"]["userAP"] = 100;
    }
    $_SESSION["AP_UPDATE"]=true;
}       

unset($_SESSION["questId"]);
unset($_SESSION["enemyStMst"]);
unset($_SESSION["enemySt"]);
unset($_SESSION["partySt"]);
unset($_SESSION["judgedatas"]);
unset($_SESSION["notesdata"]);
unset($_SESSION["Score"]);
unset($_SESSION["Comb"]);

//マイページに戻る
header( "Location: ../../Mypage.html" );
exit();

?>