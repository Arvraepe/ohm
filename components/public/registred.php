<?php
	if(isset($_GET['resend'])){
		// resend activation
		$user = GetUserById($_SESSION['activated']);
		$activation = generateRandomString(50);
		$db->query("UPDATE `user` SET activation = '".$activation."', activation_release = DATE_ADD(NOW(), INTERVAL 2 HOUR)") or die($db->error);

		// Send Mail
		$to      = $user->email;
		$subject = 'Online Handball Activation';
		$message = "Hello <strong>".$user->username."</strong>, <p>You are just one click away from becoming a true handball manager. ".
		"Please click (or copy to the address bar) the link below to activate your account!</p>".
		"<p>Link: <a href=\"http://localhost/ohm/activation/activate.php?a=".$activation."&u=".$_SESSION['activation']."\">http://http://localhost/activation/activate.php?a=".$activation."&u=".$id."</a></p>"
		."Thank you and have fun,<br />The Online Handball Team";
		$headers = "Content-type: text/html\r\n"; 
		$headers .= 'From: handballmanager@arvraepe.org';


		//mail($to, $subject, $message, $headers);
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

	$user = GetUserById($_SESSION['activated']);
	$good = '<span class="label label-success">Link valid until '.$user->activation_release.'</span>';
	$bad = '<span class="label label-important">Link expired, resend mail.</span>';
	if(date("Y-m-d H:i:s") <= $user->activation_release) $label = $good;
	else $label = $bad;
?>
<div class="row-fluid">
	<h3>Your account, <?=$user->username?> has not yet been activated!</h3>
	<div class="well">
		<strong>Activation status </strong><?=$label?>
		<h4>Check your emails (also check spam folder)</h4> <small>The mail takes a time to get through. Be patient.</small>
		<h4>No Email? Try to resend the activation mail</h4> <small><a class="btn btn-primary" href="index.php?p=registred&resend">Resend email</a></small>
	</div>
</div>