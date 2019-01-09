<?php 
 include '../php/Eventselifileload.php';

 $selif = ($_POST['data'])? $_POST['data']:1;

 $selifres = json_encode($seliflist[$selif]);

 echo $selifres;
?>