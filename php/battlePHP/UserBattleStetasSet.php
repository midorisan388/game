<?php
//ページ読み込み時,最初に必ず呼ばれる
error_reporting(E_ALL);
ini_set('error_log', '/tmp/php.log');
ini_set('log_errors', true);
ini_set('display_errors',"On");
    
require_once("./battleStetasSetUp.php");//戦闘キャラのステータス格納

$questid =  $_SESSION["QUEST_ID"];//クエストID取得
$_SESSION["questId"] = $questid;

require_once("./questDataTest.php");//クエストデータのセットアップ

    $_SESSION["judgedatas"]=array("MISS"=>0,"BAD"=>0,"GOOD"=>0,"GREAT"=>0,"PARF"=>0,"COMB"=>0);//判定データ初期化
    $_SESSION["notesdata"]=array();//ノーツデータ初期化
    $_SESSION["Score"]=0;//スコアデータ初期化
    $_SESSION["Comb"]=0;//コンボデータ初期化

    try{

                $notesUrl = $musicData["notesFilePath"];//ノーツファイルパス
                if(file_exists($notesUrl)){
                  $notesjson = file_get_contents($notesUrl);
                  $notesjson = mb_convert_encoding($notesjson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                }else{
                    $notesjson="ファイルがありません";
                }

                $_SESSION["notesdata"]=$notesjson;
                
            //オーディオデータ------------------------------------------------------------------------
            $audiotext = $musicData["audioFilePath"]; //$musicdata[3];　//audioファイルのパス
            $titletext= $questData["title"];//$questdata[1]; //クエスト名文字列
            /*--------------------------------------------------------------------------------------*/
            
            $resdata = array(
                "notesdata"=> $notesjson,//ノーツファイルのurl
                "audiohtml"=> $audiotext ,//audioタグの情報
                "title"=>$titletext,//タイトル
                "enemySt"=>$_SESSION["enemySt"],//敵ステータス
                "partySt"=>$_SESSION["partySt"]//パーティステータス
            );

            header('Content-Type: application/json; charset=utf-8');
            $resjson = json_encode($resdata, JSON_PRETTY_PRINT);
            
            echo $resjson;

    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
  }

?>