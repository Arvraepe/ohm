<?php
	$skip_page_control = true;
	require("../general.php");

	// $p = GetPlayerById(58);

	// $salary = 500;
	// $salary += $p->strength + $p->accuracy + $p->intelligence + $p->reflexes + $p->teamplay + $p->speed;
	// $salary += $p->experience;
	// $salary += $p->talent * 200;
	// echo($salary);

	// $salt = RandomHash();
	// $password = "test";

	// echo("Salt: ".$salt." Password: ".md5($password.$salt));

	// $teams = QUERY("SELECT * FROM `team` WHERE id NOT IN (SELECT tid FROM user WHERE tid is not null) ORDER BY did ASC");
	// var_dump($teams);
	session_destroy();
?>