<?php
    
    ini_set('display_errors',"On");
    error_reporting(E_ALL);
    
    $memberid = (int)$_POST("mid");
    $Stetasmode = $_POST["stetaspara"];

    $csvpath="../../csv/CharactersStetas.csv";

    try{
        require_once("../../datas/gamesystemlistsql.php");
        require_once("../getcharacterlist.php");
        require_once("../ActionSkillList.php");

           //SQL接続-----------------------------------------------------------------
           $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
           $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
           $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
           //----------------------------------------------------------------------
          $characterSt = CharacterDataSet($memberid,$csvpath);

        if($Stetasmode === "HP"){
              echo $characterSt[16];
        }else  if($Stetasmode === "Pow"){
            $damage = $characterSt[17]+random_int(10,50);
            echo  $damage;
        }else  if($Stetasmode === "Def"){
            echo $characterSt[18];
        }

    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
  }
  

?>