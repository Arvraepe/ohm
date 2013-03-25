<?php
	$skip_page_control = true;
	require("../general.php");

	if($_POST['username'] != "" && strlen($_POST['username']) > 3){

		$user = QUERY("SELECT * FROM `user` WHERE username = '".mysql_escape_string($_POST['username'])."'");

		if($user == NULL)
			echo("true");
		else
			echo("false");
	} else {
		echo("false");
	}
?>