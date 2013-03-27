<?php 
	$skip_page_control = true;
	require("../general.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$redirect = $_POST['redirect'];

	$user = GetUserByUsername(ucfirst(strtolower($username)));
	// Needs hashing
	if($user != NULL && $user->password == md5($password.$user->salt)) {

		if($user->activation != NULL){
			$_SESSION['activated'] = $user->id;
			header("location: ../index.php?p=registred");
		} else {
			SetSessionVars($user);
			header("location: ../index.php?p=dashboard");
		}
	} else {
		header("location: ../index.php?p=login&w");
	}
?>