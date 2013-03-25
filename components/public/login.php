<?php 
	$redirect = "";
	if (isset($_GET['redirect'])) { 
		$redirect = $_GET['redirect'];
	?>
	<div class="alert alert-error">
		You have to be logged in to view this page. 
		Please <strong>login</strong> or 
		<a style="margin-left: 10px; margin-right: 10px;" class="btn btn-primary btn-small" href="index.php?p=register">register</a> if you haven't already.
	</div>
<?php } else if (isset($_GET['w'])) { ?>
	<div class="alert alert-error">
		Combination username and password were incorrect. Did you <a href="">forget your password</a>?	
	</div>
<?php } ?>
<form class="form-signin" action="scripts/scr_login.php" method="POST">
	<h2 class="form-signin-heading">Sign in</h2>
	<input type="hidden" name="redirect" value="<?=$redirect?>" />
	<input type="text" name="username" class="input-block-level" placeholder="Username">
	<input type="password" name="password" class="input-block-level" placeholder="Password">
	<label class="checkbox">
	  <input type="checkbox" value="remember-me"> Remember me
	</label>
	<button class="btn btn-large btn-primary" type="submit">Sign in</button>
</form>