<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
   include 'includes/header.php';
 //  include 'includes/navigation.php';
   
   $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
   $email = rtrim($email);
   $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
   $password = rtrim($password);
   $hashed = password_hash($password, PASSWORD_DEFAULT);
   $errors = array();
?>
<style type="text/css"></style>
<div id="login_form">
  <div>
  	<?php
       if ($_POST) {
       	 //form validation
       	if (empty($_POST['email']) || empty($_POST['password'])) {
       		$errors[] = 'You must provide email and password';
       	}

       	//email validation
       	if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
       		$errors[] = 'You must enter a valid email';
       	}

       	//validating password
       	if (strlen($password)<6) {
       		$errors[] = 'Password must be more than 6 characters';
       	}

       	//checking email exits in database or not
       	$usersquery = $db->query("select * from users where email='$email'");
       	$users = mysqli_fetch_assoc($usersquery);
       	$userscount = mysqli_num_rows($usersquery);
       	if($userscount < 1) {
       		$errors[] = 'The entered email doesnot exits.';
       	}
       	if (!password_verify($password, $users['password'])) {
       		$errors[] = 'The password doesnot match our records. Please try again.';
       	}

       	//check for errors
       	if (!empty($errors)) {
       		echo display_errors($errors);
       	} else {
       		//login user
       		$user_id = $users['id'];
       		login($user_id);
       	}
       }
  	?>
  </div>
  <h2 class="text-center">Login</h2><hr>
	<form method="POST" action="login.php">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="text" name="email" class="form-control"  value="<?=$email; ?>" />
		</div>
			<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" class="form-control" value="<?=$password; ?>" />
		</div>
		<div class="form-group">
			<input type="submit" name="login" class="btn btn-primary" value="Log in" />
		</div>
	</form>
	<p class="text-right"><a href="/ecommerce/index.php">Visit Site</a></p>
</div>
<?php include 'includes/footer.php'; ?>