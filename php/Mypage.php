<?php
  require 'password.php';
  session_start();

    ini_set('display_errors',"On");
    error_reporting(E_ALL);

    // $id_cookie= $_COOKIE["userid_cookie"];
    $id_cookie = $_SESSION['userid'];
    $pass_cookie= $_COOKIE["userpass_cookie"];
    //$num_cookie=$_COOKIE["usernum_cookie"];
    $ipAdd = $_SERVER['REMOTE_ADDR'];
    $ipLong = ip2long($ipAdd);
    $APhealtime = 10;//分


    $userdata=array(
      'userID'=>$id_cookie,
      'username'=>"",
      'userrank'=>0,
      'userunitID'=>0,
      'userTitleID'=>0,
      'userStage'=>0,
      'userAP'=>0,
      'userMaxAP'=>0,
      'userexp'=>0,
      'usernextexp'=>0,
      'userpass'=>$pass_cookie,
      'usernum'=>0,
      'userstartdate'=>0,
      'userlastdate'=>0,
      'userAPupdatedate'=>0,
    );

    try{

      //SQL接続-----------------------------------------------------------------
      require_once("../datas/sql.php");
      $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
      $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
      $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      //----------------------------------------------------------------------

          $userinfo=$sql_list->query("CALL getuseraccountdata('$id_cookie')");
          $kekka=$userinfo->fetch();


            $userdata['userID']=$kekka['userID'];
            $userdata['username']=$kekka['username'];
            $userdata['userexp']=$kekka['userhaveExp'];
            $userdata['userAP']=$kekka['playerAP'];
            $userdata['userMaxAP']=$kekka['playerMaxAP'];
            $userdata['usernextexp']=$kekka['usernextExp'];
            $userdata['userrank']=$kekka['userRank'];

            if($kekka['playerAP'] < $kekka['playerMaxAP']){
              //AP回復処理
            }

            $userdata = array(
              "userdata"=>$userdata,
              "charaurl"=>"./img/characters/teststand2.png"
            );

            $resdata = json_encode($userdata,JSON_PRETTY_PRINT);

           echo $resdata;

    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
    }
?>
