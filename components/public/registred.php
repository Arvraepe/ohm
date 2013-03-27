<?php
	if(isset($_GET['resend'])){
		// resend activation
		$user = GetUserById($_SESSION['activated']);
		$activation = generateRandomString(50);
		$db->query("UPDATE `user` SET activation = '".$activation."', activation_release = DATE_ADD(NOW(), INTERVAL 2 HOUR) WHERE id = ".$user->id) or die($db->error);

		// Send Mail
		$to      = $user->email;
		$subject = 'Online Handball Activation';
		$message = "Hello <strong>".$user->username."</strong>, <p>You are just one click away from becoming a true handball manager. ".
		"Please click (or copy to the address bar) the link below to activate your account!</p>".
		"<p>Link: <a href=\"http://localhost/ohm/activation/activate.php?a=".$activation."&u=".$_SESSION['activation']."\">http://http://localhost/activation/activate.php?a=".$activation."&u=".$id."</a></p>"
		."Thank you and have fun,<br />The Online Handball Team";
		$headers = "Content-type: text/html\r\n"; 
		$headers .= 'From: handballmanager@arvraepe.org';


		mail($to, $subject, $message, $headers);
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
	$good = '<div class="label label-success">Link valid until '.$user->activation_release.'</div>';
	$bad = '<div class="label label-important">Link expired, resend mail.</div>';
	if(date("Y-m-d H:i:s") <= $user->activation_release) $label = $good;
	else $label = $bad;

	if($user->activation != NULL){
?>
<div class="jumbotron">
	<h1>Your account has not yet been activated!</h1>
	<div class="lead">
		<p><?=$label?></p>
		<strong><?=$user->username?></strong> has not yet been activated. Receiving the mail could take some time, 
		but make sure you check your <strong>spam folder</strong> as well. You can also try <strong>resending</strong> the activation mail.
		<p><br /><a class="btn btn-primary" href="index.php?p=registred&resend">Resend activation mail</a></p>	
	</div>
</div>
<?php 
} else {
?>
<div class="jumbotron">
	<h1>Your account has been activated!</h1>
		<a href="index.php?p=login" class="btn btn-success">login</a> 
</div>
<?php } ?>