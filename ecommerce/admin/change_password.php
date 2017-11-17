<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
   include 'includes/header.php';
 //  include 'includes/navigation.php';
   if (!is_logged_in()) {
    login_error();
   }
   $hashed = $user_data['password'];
   $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
   $old_password = rtrim($old_password);
   $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
   $password = rtrim($password);
   $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
   $confirm = rtrim($confirm);   
   $new_hashed =password_hash($password, PASSWORD_DEFAULT);
   $user_id = $user_data['id'];


   $errors = array();
?>
<style type="text/css"></style>
<div id="login_form">
  <div>
  	<?php
       if ($_POST) {
       	 //form validation
       	if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
       		$errors[] = 'You must provide email and password';
       	}

       	

       	//validating password
       	if (strlen($password)<6) {
       		$errors[] = 'Password must be more than 6 characters';
       	}

       	//checking new password matchesconfirm
        if ($password != $confirm) {
          $errors[] = 'The new password and the conformed password does not match.';
        }
       	
       	if (!password_verify($old_password, $hashed)) {
       		$errors[] = 'The password doesnot match our records. Please try again.';
       	}

       	//check for errors
       	if (!empty($errors)) {
       		echo display_errors($errors);
       	} else {
       		//change password
          $db->query("update users set password='$new_hashed' where id='$user_id'");
          $_SESSION['success_flash'] = 'Your password has been updated successfully.';
          header('Location: index.php');
       	}
       }
  	?>
  </div>
  <h2 class="text-center">Change password</h2><hr>
	<form method="POST" action="change_password.php">
		<div class="form-group">
			<label for="old_password">Old password:</label>
			<input type="password" name="old_password" class="form-control"  value="<?=$old_password; ?>" />
		</div>
			<div class="form-group">
			<label for="password">New Password:</label>
			<input type="password" name="password" class="form-control" value="<?=$password; ?>" />
		</div>
    <div class="form-group">
      <label for="confirm">Confirm New Password:</label>
      <input type="password" name="confirm" class="form-control" value="<?=$confirm; ?>" />
    </div>
		<div class="form-group">
			<input type="submit" name="change_password" class="btn btn-primary" value="Change password" />
      <a href="index.php" class="btn btn-default">Cancel</a>
		</div>
	</form>
	<p class="text-right"><a href="/ecommerce/index.php">Visit Site</a></p>
</div>
<?php include 'includes/footer.php'; ?>