<?php

    error_reporting(E_ALL);
    ini_set('error_log', '/tmp/php.log');
    ini_set('log_errors', true);
    ini_set('display_errors',"On" );

    $i=0;
    $gachalen = 10;//10回回す
    $gachaID=$_GET["gachaID"];

    $gachaJsonPath = "../datas/gameMasterData/gachaDataList.json";//ガチャデータ
    $itemFileName="../datas/csv/itemDataList.csv";//アイテムデータリスト-csvファイルパス
    $musicFileName="../datas/gameMasterData/musicDataList.json";//音楽データリストファイルパス

    $gachaDataArray=array();//ガチャデータ
    $gachaData =array();//使用するガチャデータ
    $itemsData = array();//出現アイテムリストデータ
    $musicData  = array();//音楽データ
    $dropItemData = array();//ガチャで出たアイテムデータ

    try{
        $gachaJson = file_get_contents($gachaJsonPath);//jsonファイル読み込み
        $gachaJson = mb_convert_encoding($gachaJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
        $gachaDataArray = json_decode($gachaJson,true);//連想配列にエンコード

        $gachaData = $gachaDataArray[$gachaID];//ガチャデータ

        $itemList = file($itemFileName);//csv読み込み

        //出現アイテムデータ格納
        for($n =0 ; $n< count($gachaData["itemGachaID"]); $n++){
            foreach($itemList as $itemline){
                if($i>0){   
                    $data = explode(",", $itemline);
                    $itemID = (int)$data[0];
                    if($itemID === $gachaData["itemGachaID"][$n]){
                        array_push($itemsData, $data);
                        break;
                    }
                }
                $i++;
            }
        }

        $musicJson = file_get_contents($musicFileName);//jsonファイル読み込み
        $musicArray = mb_convert_encoding($musicJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
        $musicArray = json_decode($musicArray,true);//連想配列にエンコード


        $musicId = (int)$gachaData["musicID"];
        $musicData = $musicArray[$musicId];//音楽データ　タイトル取得用


        //実際のガチャ実行
        for($n=0; $n<$gachalen; $n++){
            $dropItem = rand(0, count($itemsData)-1);//$itemsDataのIDを抽選
            array_push($dropItemData, $itemsData[$dropItem]);//$itemsData[$dropItem]のデータをプッシュ
        }

        $responsData=array(
            "musicTitle"=>$musicData["musictitle"],
            "itemData"=>$dropItemData
        );

        header('Content-Type: application/json; charset=utf-8');
        $resjson = json_encode( $responsData,JSON_PRETTY_PRINT );    
        echo  $resjson;

    }catch(PDOExeption $erro){
        echo "次のエラーが発生しました<br>";
        echo $erro->getmessage();
  }
?>