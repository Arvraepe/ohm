<?php

/**
 *
 * 	User Creation Script. Validate, Search Team, Generate Activation, Mail Activation, Finish
 *
 */

	$skip_page_control = true;
	require("../general.php");

	//STEP 1 VALIDATE THE INFO
	$username = $_POST['pUsername'];
	$email = $_POST['pEmail'];
	$password1 = $_POST['pPassword'];
	$password2 = $_POST['pPassword2'];
	$agreed = $_POST['pAgreement'];

	if(
		$agreed == "agreed"
		&& usernameAvailable($username)
		&& strlen($username) > 3
		&& checkEmail($email)
		&& strlen($password1) > 4
		&& $password1 == $password2

	){

		$activation = generateRandomString(50);
		$salt = generateRandomString(40);
		$db->query("INSERT INTO `user` (username, password, salt, email, activation, activation_release)
			VALUES(
				'".$db->real_escape_string($username)."',
				'".md5($password1.$salt)."',
				'".$salt."',
				'".$db->real_escape_string($email)."',
				'".$activation."',
				DATE_ADD(NOW(), INTERVAL 2 HOUR)
				)");
		
		$id = $db->insert_id;

		// Send Mail
		$to      = $email;
		$subject = 'Online Handball Activation';
		$message = 'Hello '.$username.", \n\nYou are just one click away from becoming a true handball manager. ".
		"Please click (or copy to the address bar) the link below to activate your account!\n\n".
		'<a href="localhost/activation/activate.php?a='.$activation.'&u='.$id.'">\n\n'
		."Thank you and have fun,\nThe Online Handball Team";
		$headers = 'From: handballmanager@arvraepe.org';

		mail($to, $subject, $message, $headers);

		// DONE Redirect to login or something
		header("location: ../index.php?p=login");
	} else {
		header("location: ../index.php?p=register&e");
	} 

	function usernameAvailable($name){
		$user = QUERY("SELECT * FROM `user` WHERE username = '".mysql_escape_string($name)."'");
		return $user == NULL;
	}

	function checkEmail($email){
		$regex = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'; 
		return preg_match($regex, $email); 
	}

	function generateRandomString($length){
		$str = "abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ_0123456789";
		$act = "";
		for ($i=0; $i < $length; $i++) { 
			$irand = rand(0, strlen($str));
			$act .= $str[$irand];
		}

		return $act;
	}



?>