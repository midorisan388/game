<?php 

function getMusicData($qdata){//クエストデータ取得
    $musicData = array();//取得する音楽データ
    $musicFileName="C:\MAMP\htdocs\serverside\datas\gameMasterData\musicDataList.json";//音楽データリストファイルパス

    $musicJson = file_get_contents($musicFileName);//jsonファイル読み込み
    $musicArray = mb_convert_encoding($musicJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');//文字化け防止
    $musicArray = json_decode($musicArray,true);//連想配列にエンコード

    $id = (int)$qdata["musicData"];//IDを整数値にキャスト
    $data = $musicArray[$id];//該当するデータを取得

    return $data;// $musicDataへ返す
}

?>