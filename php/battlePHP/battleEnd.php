<?php
//バトル終了後の処理
ini_set('display_errors',"On");
error_reporting(E_ALL);

session_start();

// バトルで使用したセッションデータ削除

unset( $_SESSION["enemyStMst"]);
unset( $_SESSION["enemySt"]);
unset( $_SESSION["partySt"]);
unset( $_SESSION["judgedatas"]);
unset( $_SESSION["notesdata"]);
unset( $_SESSION["Score"]);
unset( $_SESSION["Comb"]);

//マイページに戻る
header( "Location: ../../Mypage.html" );
exit();

?>