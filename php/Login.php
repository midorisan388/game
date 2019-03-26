
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

  $login_succes=false;
  $deletecookie ="";
  $log_mes="";
  $errorMes="";

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
        else if($userdata_in===1){//一致するIDがあった
          PlayerLoginSetting($id);
        }
      }catch(PDOExeption $erro){
        echo "次のエラーが発生しました<br>";
        echo $erro->getmessage();
      }
    }
  }
?>
