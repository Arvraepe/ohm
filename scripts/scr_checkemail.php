<?php
	$skip_page_control = true;
	require("../general.php");
	if($_POST['email'] != "" && checkEmail($_POST['email'])){

		$user = QUERY("SELECT * FROM `user` WHERE email = '".mysql_escape_string($_POST['email'])."'");

		if($user == NULL)
			echo("true");
		else
			echo("false");
	} else {
		echo("false");
	}

	function checkEmail($email){
		$user = GetUserByEmail($email);
		$regex = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'; 
		return $user == NULL && preg_match($regex, $email); 
	}
?>