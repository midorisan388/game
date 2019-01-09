<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);

try{
  function CharacterDataSet($no,$filepath){
    $characterfilename=$filepath;

      $lines = file($characterfilename);
      $i=0;
    foreach($lines as $line){
      $data = explode(',',$line);
        if($i > 0){
          if($i === $no){
           return $data;
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