<?php
   require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
   $name = sanitize($_POST['full_name']);
   $email = sanitize($_POST['email']);
   $phone = sanitize($_POST['phone']);
   $street1 = sanitize($_POST['street1']);
   $street2 = sanitize($_POST['street2']);
   $city = sanitize($_POST['city']);
   $errors = array();
   $required = array(
   	'full_name' => 'Full Name',
   	'email'     => 'Email',
   	'phone'     => 'Phone',
   	'street1'   => 'Street 1',
   	'street2'   => 'Street 2',
   	'city'      => 'City',
   );

   //check if all required fields are filled validation and 
   foreach ($required as $f => $d) {
   	if (empty($_POST[$f]) || $_POST[$f] == '' ) {
   		$errors[] = $d.' is required.';
   	}
   }

   ///email varification
   if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
   	$errors[] = 'Enter valid email address';
   }

   if (!empty($errors)) {
   	echo display_errors($errors);
   } else {
   	echo 'passed';
   }
?>