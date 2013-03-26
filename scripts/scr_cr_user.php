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
		$message = "Hello <strong>".$username."</strong>, <p>You are just one click away from becoming a true handball manager. ".
		"Please click (or copy to the address bar) the link below to activate your account!</p>".
		"<p>Link: <a href=\"http://localhost/ohm/activation/activate.php?a=".$activation."&u=".$id."\">http://http://localhost/activation/activate.php?a=".$activation."&u=".$id."</a></p>"
		."Thank you and have fun,<br />The Online Handball Team";
		$headers = "Content-type: text/html\r\n"; 
		$headers .= 'From: handballmanager@arvraepe.org';


		mail($to, $subject, $message, $headers);
		$_SESSION['activationid'] = $id;

		header("location: ../index.php?p=registred"); // register != registred
	} else {
		header("location: ../index.php?p=register&e");
	} 

	function usernameAvailable($name){
		$user = GetUserByUsername($name);
		return $user == NULL;
	}

	function checkEmail($email){
		$user = GetUserByEmail($email);
		$regex = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'; 
		return $user != NULL && preg_match($regex, $email); 
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