<?php
//クエストデータとアイテムIDの整合性チェック
error_reporting(E_ALL);
ini_set('error_log', '/tmp/php.log');
ini_set('log_errors', true);
ini_set('display_errors',"On" );

require_once("../getDataMusic.php");

$qId = $_SESSION["questId"];
try{
        
    //使用データ格納・連想配列初期化
        $musicData = array();
        $questData =array();
        $itemDatas=array();
        
        $questFileName="../../datas/gameMasterData/questDataList.json";//クエストデータリストファイルパス
        $musicFileName="../../datas/gameMasterData/musicDataList.json";//音楽データリストファイルパス
        $itemFileName="../../datas/csv/itemDataList.csv";//アイテムデータリスト-csvファイルパス

            $questJson = file_get_contents($questFileName);//jsonファイル読み込み
            $questJson = mb_convert_encoding($questJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
            $questArray = json_decode($questJson,true);//連想配列にエンコード

           /* $musicJson = file_get_contents($musicFileName);//jsonファイル読み込み
            $musicArray = mb_convert_encoding($musicJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
            $musicArray = json_decode($musicArray,true);//連想配列にエンコード
*/
           // $itemList = file($itemFileName);//csvファイル読み込み

            $i=0;
        foreach($questArray as $questline){//データ探索
            $data = $questline;//一レコードデータ格納
                $id = $data["ID"];//判定用ID格納
                if($qId === $id){//該当クエストデータ取得
                    $questData = $data;//該当クエストデータ格納
                    $musicData = getMusicData($questData);//ミュージックデータ探索
                    //$itemDatas = getItemData($musicData, $itemList);

                      break;
                 }
         }
    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
    }

    /*
    function getMusicData($qdata, $musicarray){
        $id = (int)$qdata["musicData"];//IDを整数値にキャスト
        $data = $musicarray[$id];//該当するデータを取得

        return $data;// $musicDataへ返す
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
    */
?>
