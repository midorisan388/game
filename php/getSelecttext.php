<?php 
 include '../php/Eventselifileload.php';

 $select = ($_POST['data'])? $_POST['data']:0;

$selectline = json_encode($selectlist[$select]);


 echo $selectline;
?>