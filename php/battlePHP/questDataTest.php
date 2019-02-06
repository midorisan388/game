<?php
//クエストデータとアイテムIDの整合性チェック
error_reporting(E_ALL);
ini_set('error_log', '/tmp/php.log');
ini_set('log_errors', true);
ini_set('display_errors',"On" );

$qId = $_SESSION["questId"];
try{
        
        $musicData = array();
        $questData =array();
        $itemDatas=array();
        
        $questFileName="../../datas/gameMasterData/questDataList.json";//クエストデータリストファイルパス
        $musicFileName="../../datas/gameMasterData/musicDataList.json";//音楽データリストファイルパス
        $itemFileName="../../datas/csv/itemDataList.csv";//アイテムデータリスト-csvファイルパス

            $questJson = file_get_contents($questFileName);//jsonファイル読み込み
            $questJson = mb_convert_encoding($questJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
            $questArray = json_decode($questJson,true);//連想配列にエンコード

            $musicJson = file_get_contents($musicFileName);//jsonファイル読み込み
            $musicArray = mb_convert_encoding($musicJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
            $musicArray = json_decode($musicArray,true);//連想配列にエンコード

            $itemList = file($itemFileName);//csvファイル読み込み

            $i=0;
        foreach($questArray as $questline){//データ探索
            $data = $questline;
                $id = $data["ID"];
                if($qId ===$id){//該当クエストデータ取得
                    $questData = $data;
                    $musicData = getMusicData($questData, $musicArray);
                    //$itemDatas = getItemData($musicData, $itemList);

                      break;
                 }
         }
    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
    }

    function getMusicData($qdata, $musicarray){
        $i=0;

        foreach($musicarray as $musicline){//データ探索
            $data = $musicline;
    
            $id = $data["ID"];
            if($qdata["musicData"] === $id){//該当音楽データ取得
                 return $data;
            }
        }
    }

    function getItemData($mdata, $itemarray){
                
        for( $n=0; $n < count($mdata["itemGachaID"]); $n++){
            $i=0;
            foreach($itemarray as $itemline){//データ探索
                $data = explode(",", $itemline);
                if($i > 0){
                $id = $data[0];//ID格納
                if($mdata["itemGachaID"][$n] === $i){//該当アイテムデータ取得
                        array_push($itemDatas,$data);
                        break;
                    }
                 }
                $i++;
            }
        }   
    }
?>
