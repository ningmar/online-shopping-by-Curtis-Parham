<?php

   $db = mysqli_connect("localhost","root","","tutorial");
   if(mysqli_connect_errno())
   {
   	echo "database cannot be connected at a time ".mysqli_connect_error();
   	die();
   }

  // define('BASEURL','/ecommerce/'); 
   session_start();
   require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
   require_once BASEURL.'/ecommerce/helpers/helpers.php';
   //require_once BASEURL.'/vendor/autoload.php';       //OOp with stripe

   $cart_id = '';
   if(isset($_COOKIE[CART_COOKIE])){
      $cart_id = sanitize($_COOKIE[CART_COOKIE]);
   }

   if (isset($_SESSION['Suser'])) {
      $user_id =$_SESSION['Suser'];
      $query = $db->query("select * from users where id = '$user_id'");
      $user_data = mysqli_fetch_assoc($query);
      $fn = explode(' ', $user_data['full_name']);
      $user_data['first'] = $fn[0];
      $user_data['last'] = $fn[1];
   }

   if (isset($_SESSION['success_flash'])) {
      echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
      unset($_SESSION['success_flash']);
   }

   if (isset($_SESSION['error_flash'])) {
      echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
      unset($_SESSION['error_flash']);
   }
?>