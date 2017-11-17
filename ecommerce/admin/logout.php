<?php 
   require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
   unset($_SESSION['Suser']);
   header('Location: login.php');
 
?>