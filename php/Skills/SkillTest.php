<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

try{

    /*テストデータ-------------------------------------------------*/
    $enemySt = array(
        "name"=>"エネミー",
        "skillName"=>"",
        "HP"=>1000,
        "Pow"=>100,
        "damage"=>0,
        "stetas"=>array(/*状態異常*/)
    );
    
    $playerSt = array(
        "name"=>"プレイヤー",
        "skillName"=>"",
        "HP"=>1000,
        "Pow"=>100,
        "damage"=>0,
        "stetas"=>array(/*状態異常*/)
    );
    /*--------------------------------------------------------------*/
    $i=0;
   //スキルID読み込み
   $skillData=array();//レコード格納
   $skillArgument = array();//引数2に渡す配列

   $sID=5;//読み込むスキルID
   $skillFileLine = file("../../datas/csv/SkillList.csv");
   $skillLineLength = count($skillFileLine);

   foreach( $skillFileLine as $skillLine){
       $sdata=explode(',', $skillLine);
       if($i > 0){//最初の行は飛ばす
        $sdataIndex = (int)$sdata[0];
           if($sdataIndex === $sID){//スキルデータレコードにヒット
                $skillData = $sdata;

               for($n=3; !empty($skillData[$n]); $n++){
                   array_push($skillArgument, $skillData[$n]);//ステータス引数配列作成
               }
               var_dump($skillData);//使用するレコード
                break;
           }
       }
       $i++;
   }

   if($i < $skillLineLength){
    require_once("./".$skillData[2].".php");//該当クラスファイル読み込み
    //引数2には$skillData[3]以降のデータをまとめた配列を渡す
                        //スキルクラス名   スキル名　　　引数リスト
    $skillClassObj = new $skillData[2]($skillData[1],$skillArgument);//バトルメンバーのスキルに格納する
    $playerSt["skillName"]=$skillClassObj;
    
    $playerSt["skillName"]->skillaction($playerSt,$enemySt);
   }else{
    echo "見つかりませんでした<br>";
   }
  }catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
  }

?>