<?php
	$skip_page_control = true;
	require("../general.php");
$salt = "aPiHzTIMCM1srM91klF4n38Y7UcgaeL1uu9kD";

echo(md5("test".$salt));
?>