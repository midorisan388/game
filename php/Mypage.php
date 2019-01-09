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


    }catch(PDOExeption $erro){
      echo "次のエラーが発生しました<br>";
      echo $erro->getmessage();
    }
?>

<!DOCTYPE html>
 <html>
 <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="css/Home.css" type="text/css" />
  <script src="js/HomeSprite.js"></script>
  <script type="text/javascript" src="js/prag/jquery-3.3.1.js"></script>
  <title>HOME</title>

 </head>

<!--ホーム画面-->
 <body>
   <div id="rightvire">
     <div class="main">
      <div id="Home" class="Home">
        <img draggable="false" id="back" class="back" src="img/menu/hometest2.jpg" alt="背景" />
        <div id="userdata" class="userdata">
        <div id="userstatusmenu"> 
          <!--?php   echo $ipAdd."<br>";echo $ipLong; ?-->
          <div class="username" id="username"><?php echo $userdata['username'];?> </div>
          <div class="userlank" id="userlank">RANK:<?php echo $userdata['userrank'];?> </div>
          <div class="userexp" id="userexp">経験値:<?php echo $userdata['userexp']."/".$userdata['usernextexp'];?></div>
       </div>
        </div>
          <img draggable="false" id="homechara" class="homechara" src="img/characters/teststand2.png" alt="キャラ" />
          <div class="topcharacter-selif"><div id="selif-frame"><div id="selif-backimage">何見てんだ</div></div></div>
      </div>
    </div>
  </div>

 </body>
 </html>
