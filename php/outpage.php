<?php
require 'password.php';
session_start();

  ini_set('display_errors',"On");
  error_reporting(E_ALL);

  unset($_SESSION['userid']);

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Page Title</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <p>ログアウトしました</p>
    <p><a href="../LoginHome.html">ログイン画面へ</a></p>
</body>
</html>

