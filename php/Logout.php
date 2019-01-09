<?php
  ini_set('display_errors',"On");
  error_reporting(E_ALL);
  echo "Cookieさくじょ ";
try{
  setcookie("userid_cookie",'',time()-60*60*24*30);
  setcookie("userpass_cookie",'',time()-60*60*24*30);
  setcookie("usernum_cookie",'',time()-60*60*24*30);

  header( "Location: Loginhome.html" );
  exit();
}catch(PDOExeption $erro){
  echo "次のエラーが発生しました<br>";
  echo $erro->getmessage();
}

?>
<html lang="ja">
<head>
  <meta charset="utf-8">
</head>
<body>
  <a href="Loginhome.html">ログインページへ戻る</a>
</body>
</html>
