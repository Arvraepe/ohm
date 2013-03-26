<?php
	$skip_page_control = true;
	require("../general.php");

	// $p = GetPlayerById(58);

	// $salary = 500;
	// $salary += $p->strength + $p->accuracy + $p->intelligence + $p->reflexes + $p->teamplay + $p->speed;
	// $salary += $p->experience;
	// $salary += $p->talent * 200;
	// echo($salary);

	$salt = RandomHash();
	$password = "test";

	echo("Salt: ".$salt." Password: ".md5($password.$salt));
?>