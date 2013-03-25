<?php 
	$skip_page_control = true;
	require("../general.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$redirect = $_POST['redirect'];

	$user = SearchUserByUsername(ucfirst(strtolower($username)));
	// Needs hashing
	if($user != NULL && $user->password == md5($password.$user->salt)) {
		$_SESSION['uid'] = $user->id;
		$_SESSION['tid'] = $user->tid;
		$_SESSION['username'] = $user->username;
		header("location: ../index.php?p=dashboard");
	} else {
		header("location: ../index.php?p=login&w");
	}
?>