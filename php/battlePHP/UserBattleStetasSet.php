<?php
error_reporting(E_ALL);
ini_set('error_log', '/tmp/php.log');
ini_set('log_errors', true);
    
    require_once("./battleStetasSetUp.php");
    $_SESSION["judgedatas"]=array("MISS"=>0,"BAD"=>0,"GOOD"=>0,"GREAT"=>0,"PARF"=>0,"COMB"=>0);
    $_SESSION["notesdata"]=array();//ノーツデータ更新
    $_SESSION["COMB"]=0;
    $_SESSION["Score"]=0;
    try{
           //SQL接続-----------------------------------------------------------------
           require_once("../../datas/gamesystemlistsql.php");
           $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
           $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
           $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
           //----------------------------------------------------------------------
           //$questid = (int)$_POST["questid"];
           $questid=1;
           $questdata = $sql_list->query("SELECT * FROM quests_table WHERE QuestID = $questid");
           $questdata=$questdata -> fetch();
           $musicId = (int)$questdata[2];
           $musicdata = $sql_list->query("SELECT * FROM  musicfiletable WHERE musicID = $musicId");
           $musicdata =$musicdata -> fetch();
   
            $notesUrl="../../datas/Notesfile/".$musicdata[2];
            if(file_exists($notesUrl)){
              $notesjson = file_get_contents($notesUrl);
              $notesjson = mb_convert_encoding($notesjson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
             }else{
                 $notesjson="ファイルがありません";
            }

            $_SESSION["notesdata"]=$notesjson;

            $audiotext =  $musicdata[3];
            $titletext=$questdata[1];

            $resdata = array(
                "notesdata"=> $notesjson,//ノーツファイルのurl
                "audiohtml"=> $audiotext ,//audioタグの生成
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