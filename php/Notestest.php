<?php

function NotesdataGet(){
    ini_set('display_errors',"On");
    error_reporting(E_ALL);

    require_once("../datas/gamesystemlistsql.php");
    $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
    $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
    $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //----------------------------------------------------------------------
    $questid = 1;
    $questdata = $sql_list->query("SELECT * FROM quests_table WHERE QuestID = $questid");
    $questdata=$questdata -> fetch();
    $musicId = (int)$questdata[2];
    $musicdata = $sql_list->query("SELECT * FROM  musicfiletable WHERE musicID = $musicId");
    $musicdata =$musicdata -> fetch();

    $jsonUrl="../".$musicdata[2];
    if(file_exists($jsonUrl)){
        $json = file_get_contents($jsonUrl);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        echo $json;
    }else{
        echo "ファイルがありません";
    }
}
?>