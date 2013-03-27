<?php
	$skip_page_control = true;
	require("../general.php");

	if(isset($_GET['a']) && isset($_GET['u'])){
		$activation = $_GET['a'];
		$uid = $_GET['u'];

		// Look for user by id and do it securely
		$user = GetUserById($uid);
		
		// Redirect when the found user == null
		if($user == NULL) header("location: ../index.php?p=login&wa");
		if($user->activation_release == NULL) header("location: ../index.php?p=login&wa");
		
		//TODO check if activation_release has passed...

		// Finally, activation has been successful
		if($user->activation == $activation) {
			// SO IT BEGINS -> search for a team for this user (NOT IN: googlen)
			$teams = QUERY("SELECT * FROM `team` WHERE id NOT IN (SELECT tid FROM user WHERE tid is not null) ORDER BY did ASC");
			if(count($teams) > 0){
				$pteam = $teams[0];
				$db->query("UPDATE `user` SET activation = NULL, activation_release = NULL, tid = ".$pteam->id." WHERE id = ".$user->id);
				
				// Setting the session vars needed
				$user->tid = $pteam->id;
				//var_dump($user);
				SetSessionVars($user);

				header("location: ../index.php?p=dashboard");
			} else {
				// Eum probleemke... geen teams meer? What to do?
				// Possibility 1: Create new division + simulate games up until now
				// Possibility 2: Put on waiting list for when current season is over
				echo("If you are reading this, all the teams are allocated and there is a placeholder of actions here... I don't know yet what to do with this issue");
			}
		}
	}else{
		header("location: ../index.php");
	}
?>