<?php
//ページ読み込み時,最初に必ず呼ばれる
error_reporting(E_ALL);
ini_set('error_log', '/tmp/php.log');
ini_set('log_errors', true);
ini_set('display_errors',"On");

    
require_once("./battleStetasSetUp.php");

    //更新中のセッションが無ければ初期値を設定
  /*  if(!isset($_SESSION["judgedatas"])) $_SESSION["judgedatas"]=array("MISS"=>0,"BAD"=>0,"GOOD"=>0,"GREAT"=>0,"PARF"=>0,"COMB"=>0);//判定データ初期化
    if(!isset($_SESSION["notesdata"])) $_SESSION["notesdata"]=array();//ノーツデータ初期化
    if(!isset($_SESSION["Score"])) $_SESSION["Score"]=0;//スコアデータ初期化
    if(!isset($_SESSION["COMB"]))  $_SESSION["COMB"]=0;//コンボデータ初期化
    */
    $_SESSION["judgedatas"]=array("MISS"=>0,"BAD"=>0,"GOOD"=>0,"GREAT"=>0,"PARF"=>0,"COMB"=>0);//判定データ初期化
    $_SESSION["notesdata"]=array();//ノーツデータ初期化
    $_SESSION["Score"]=0;//スコアデータ初期化
    $_SESSION["COMB"]=0;//コンボデータ初期化

    try{
           //SQL接続-----------------------------------------------------------------
           require_once("../../datas/gamesystemlistsql.php");
           $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
           $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
           $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
           //----------------------------------------------------------------------
           //$questid = (int)$_POST["questid"];

           //クエスト情報----------------------------------------------------------------------------
           $questid=1;

           $questdata = $sql_list->query("SELECT * FROM quests_table WHERE QuestID = $questid");
           $questdata=$questdata -> fetch();
           $musicId = (int)$questdata[2];
           $musicdata = $sql_list->query("SELECT * FROM  musicfiletable WHERE musicID = $musicId");
           $musicdata =$musicdata -> fetch();
            //ノーツデータ----------------------------------------------------------------------------
                $notesUrl="../../datas/Notesfile/".$musicdata[2];
                if(file_exists($notesUrl)){
                $notesjson = file_get_contents($notesUrl);
                $notesjson = mb_convert_encoding($notesjson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                }else{
                    $notesjson="ファイルがありません";
                }
            if(!isset($_SESSION["notesdata"])){

                $_SESSION["notesdata"]=$notesjson;
            }
            //オーディオデータ------------------------------------------------------------------------
            $audiotext =  $musicdata[3];
            $titletext=$questdata[1];
            /*--------------------------------------------------------------------------------------*/
            
            $resdata = array(
                "notesdata"=> $notesjson,//ノーツファイルのurl
                "audiohtml"=> $audiotext ,//audioタグの情報
                "title"=>$titletext,//タイトル
                "enemySt"=>$_SESSION["enemySt"],
                "partySt"=>$_SESSION["partySt"]
            );


            header('Content-Type: application/json; charset=utf-8');
            $resjson = json_encode($resdata, JSON_PRETTY_PRINT);
            
            echo $resjson;

    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
  }

?>