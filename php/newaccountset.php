<?php
  require 'password.php';
  session_start();
  
  ini_set('display_errors',"On");
  error_reporting(E_ALL);

  $login_succes=false;
  $log_mes="アカウント登録";
  $errorMes="";
    //ユーザー名　　ユーザーID   ユーザーパスワード  ユーザーナンバー
  $userdata=array(
    'userID'=>"",
    'userpass'=>"",
    'usernum'=>"",
    'username'=>""
  );

 if(isset($_POST["Create"])){
    if(empty($_POST["userid"])){
      $errorMes="<br>ユーザーIDを入力してください";
    }
    if(empty($_POST["userpass"])){
      $errorMes="<br>パスワードを入力してください";
    }
    if(empty($_POST["username"])){
      $errorMes="<br>ユーザー名を入力してください";
    }

   if(!empty($_POST['userid']) && !empty($_POST['userpass']) && !empty($_POST['username'])){
      $id = $_POST['userid'];
      $pass =$_POST['userpass'];
      $name = $_POST['username'];
      try{
          //SQL接続-----------------------------------------------------------------
          require_once("../datas/sql.php");
          $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
          $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
          $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
          //----------------------------------------------------------------------
          $s = "SELECT * FROM useraccountdata";
          $row=$sql_list->query($s);

          while($kekka=$row->fetch()){
            if($kekka['userID']==$id){
              $log_mes = "すでにこのIDは使用されています";
              echo $kekka['userID'];
              $login_succes=false;
              break;
            }else{
              $login_succes=true;
            }
          }

          if($login_succes == true){
              $userdata['userID'] = $id;
              $userdata['userpass'] = $pass;
              $userdata['username']= $name;

              //アカウント追加処理
              $sql_list->query("CALL adduser('$id','$pass','$name')");
              $log_mes = "新しく登録されました<br><a href='../Login.php'>ログインに戻る</a>";
              $login_succes=true;
          }else{
            echo "<br>すでにこのIDは使用されています";
          }
         }
        catch(PDOExeption $erro){
            echo "次のエラーが発生しました<br>";
            echo $erro->getmessage();
        }
      }
  }
  
?>

<html>
<head>
    <meta charset="utf-8">
    <title>新規登録</title>
</head>
<body>
  新規登録画面:
    <form action="" method="post">
       ID:<input name="userid" type="text" size="5">
       PASS:<input name="userpass" type="text" size="12">
       ユーザー名:<input name="username" type="text" size="8">※ユーザー名は登録後も変更できます
      <input type="submit" name="Create" value="登録">
      <input type="hidden" name="login" value="newlogin">
    </form>
    <?php 
      echo $log_mes; 
      echo $errorMes; 
    ?>
</body>
</html>
