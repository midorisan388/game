
<?php
require 'password.php';
session_start();

  ini_set('display_errors',"On");
  error_reporting(E_ALL);

  function PlayerLoginSetting($id_s){
    $_SESSION['userid']=$id_s;//ユーザーID保持
    header( "Location: ../Mypage.html" );
    exit();
  }

  $cookieid="";
  $cookiepass="";
  $cookienum="";

  if(isset($_GET["idmemory"])){
    if(isset($_COOKIE['userid_cookie'])){
      if(isset($_COOKIE['userpass_cookie'])){
         $cookiecheck=true;
 
         $id = $cookieid = $_COOKIE['userid_cookie'];
         $cookiepass = $_COOKIE['userpass_cookie'];

         PlayerLoginSetting($id);

      }
    }else{
      echo "Cookieが存在しません";
    }
  }
 

  $login_succes=false;

   //ユーザー名　　ユーザーID   ユーザーパスワード  ユーザーナンバー
   $userdata=array(
    'userID'=>"",
    'userpass'=>"",
    'usernum'=>"",
    'username'=>""
  );

  $deletecookie ="";
  $log_mes="";
  $errorMes="";
  $cookiesetting=false;


if(isset($_GET["Loginbtn"])){
  if(empty($_GET["userid"])){
    $log_mes= "userid is empty";
    header( "Location:../LoginHome.html?error=".$log_mes );
    exit();
  }
  if(empty($_GET["userpass"])){
    $log_mes= "userpsw is empty";
    header( "Location: ../LoginHome.html?error=".$log_mes );
    exit();
  }
//ログイン処理開始
  if(!empty($_GET["userid"]) && !empty($_GET["userpass"])){
    $id=$_GET["userid"];
    $pass=$_GET["userpass"];
    $userdata_in=0;

      try{
        //SQL接続-----------------------------------------------------------------
        require_once("../datas/sql.php");
        $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
        $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
        $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //----------------------------------------------------------------------
        $s = $sql_list->exec("CALL UpdateuserPlayDate('$id')");//最終ログイン時間更新
       
        $row=$sql_list->query("CALL getuser('$id','$pass')");

        while($dbinfo = $row -> fetch(PDO::FETCH_ASSOC)){
          if($id === $dbinfo['userID']){
            if($pass===$dbinfo['userPass']){
               $userdata_in=1;
              break;
            }
          }
        }

        //一致するIDがなかった
        if($userdata_in===0 ){
          header( "Location: ../LoginHome.html?error=user wasn't fined" );
          exit();
        }
        else if($userdata_in===1){

              $userdata['userID']=$id;//kekka['userid'];
              $userdata["userpass"]=$pass;//kekka['userpass'];

              setcookie("userid_cookie",$id,time()+60*60*24*30);
              setcookie("userpass_cookie",$pass,time()+60*60*24*30);

              PlayerLoginSetting($id);
        }

  
      }catch(PDOExeption $erro){
        echo "次のエラーが発生しました<br>";
        echo $erro->getmessage();
      }
    }
  }
?>
