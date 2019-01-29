<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);

try{
  function CharacterDataSet($no,$filepath){
    $characterfilename=$filepath;//csvファイルパス

      $lines = file($characterfilename);//csvファイル読み込み
      $i=0;
    foreach($lines as $line){//データ探索
      $data = explode(',',$line);
        if($i > 0){
          if($i === $no){
           return $data;//csvレコードを返す
          }
      }
        $i++;
    }
  }
  }catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
  }

?>