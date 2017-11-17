<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';

if (!is_logged_in()) {
	login_error();
}
if (!has_permission('admin')) {
	permission_error('index.php');
}
   include 'includes/header.php';
   include 'includes/navigation.php';
   
   if (isset($_GET['delete'])) {
   	$delete_id = (int)$_GET['delete'];
   	$db->query("delete from users where id='$delete_id'");
   	$_SESSION['success_flash'] = 'User has been deleted from the record';
   	header('Location: users.php');
   }

   //adding new users and now html forms and all
   if (isset($_GET['add'])) { 
   	$name=((isset($_POST['name']))?sanitize($_POST['name']):'');
   	$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
   	$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
   	$confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
   	$permission=((isset($_POST['permission']))?sanitize($_POST['permission']):'');
   	$errors = array();
   	if ($_POST) {

   		$emailQuery = $db->query("select * from users where email='$email'");
   		$emailCount =mysqli_num_rows($emailQuery);
   		if ($emailCount != 0) {
   			$errors[] = 'That email already exits in our database.';
   		}
   		$required = array('name', 'email', 'password', 'confirm', 'permission');
   		foreach ($required as $fvalue) {
   			if(empty($_POST[$fvalue])) {
   				$errors[] = 'You must fill all the filled';
   				break;
   			}
   		}
   			if (strlen($password) < 6) {
   				$errors[] = 'Password must be at least 6 digits';
   			}
   			if ($password != $confirm) {
   				$errors[] = 'Entered passwords does not match.';
   			}
   			if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
   				$errors[] = 'Enter a valid email';
   			}
   			if (!empty($errors)) {
   				echo display_errors($errors);
   			}else{
   				//add new user
   				$hashed = password_hash($password,PASSWORD_DEFAULT);
   				$db->query("insert into users(full_name, email, password, permission) values('$name', '$email', '$hashed', '$permission')");
   				$_SESSION['success_flash'] = 'The new user has been entered.';
   				header('Location: users.php');
   			}
   		}
   	
   	?>
   	<h2 class="text-center">Add new user</h2><hr>
   	<form action="users.php?add=1" method="POST" class="">
   	    <div class="form-group col-md-6">
   			<label for="name">Full Name:</label>
   			<input type="name" name="name" id="name" class="form-control" value="<?=$name; ?>"/>
   		</div>
   		<div class="form-group col-md-6">
   			<label for="email">Email:</label>
   			<input type="email" name="email" id="email" class="form-control" value="<?=$email; ?>"/>
   		</div>
   		<div class="form-group col-md-6">
   			<label for="password">Password:</label>
   			<input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>"/>
   		</div>
   		<div class="form-group col-md-6">
   			<label for="confirm">Confirm Password:</label>
   			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm; ?>"/>
   		</div>
   		<div class="form-group col-md-6">
   			<label for="permission">Permission</label>
   			<select class="form-control" name="permission">
   				<option value=""<?=(($permission == '')?' selected':''); ?>></option>
   				<option value="editor"<?=(($permission == '')?' selected':''); ?>>Editor</option>
   				<option value="editor,admin"<?=(($permission == '')?' selected':''); ?>>Admin</option>
   			</select>
   		</div>
   		<div class="form-group col-md-6">
  			<a href="users.php" class="btn btn-default">Cancel</a>
   			<input type="submit" name="" value="Add user" class="btn btn-primary">
   		</div>
   	</form><hr><br><br><br><br><br><br><br><br>
<?php   
 }else {
       $userQuery = $db->query("select * from users");
   
?>
<h1>System users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="btn-addproduct">Add New User</a><hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th></th><th>Name</th><th>Email</th><th>Join date</th><th>Last login</th><th>Permission</th>
	</thead>
	<tbody>
	<?php while($user =mysqli_fetch_assoc($userQuery)): ?>
		<tr>
			<td>
				<?php if($user['id'] != $user_data['id']): ?>
					<a href="users.php?delete=<?=$user['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
				<?php endif; ?>
			</td>
			<td><?=$user['full_name']; ?></td>
			<td><?=$user['email']; ?></td>
			<td><?=pretty_date($user['join_date']); ?></td>
			<td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login'])); ?></td>
			<td><?=$user['permission']; ?></td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>
<?php }
   include 'includes/footer.php';
?>