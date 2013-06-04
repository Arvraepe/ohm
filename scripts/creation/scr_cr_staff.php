<?php

	/*
		
		Create extra staff that will be on the staff buying list
		Exists of: Name, Rank, Experience, TeamId, Type, Salary

		Salary is updated every season in the seasonal update/cron-jobs

	*/

	$skip_page_control = true;
	require("../../general.php");

	// Start salaries of the different types.. some are more essential thus more expensive
	$startsals = array(500, 500, 200, 200, 400, 300, 300);

	// The names
	$names = GetRandomName(50);

	foreach ($names as $n) {
		echo "------------------- <br />";
		echo "Name: ".$n."<br />";
		// Random Rank (Sort of starting skills)
		$experience = rand(1234,4321);
		echo "experience: ".$experience."<br />";
		echo "rank: ".floor($experience/1000)."<br />";
		// 1 = coach; 2 = trainer; 3 = psychologist; 4 = doctor; 5 = physiotherapist; 6 = scout; 7 = analyst
		$type = rand(1,7);
		echo "Type: ".$type."<br />";
		// Salary
		$salary = $startsals[$type-1] + floor($experience / 4);
		echo "Salary: ".$salary."<br />";

		$id = CreateStaff($n, $experience, $salary, $type);
		echo "Staff with ID ".$id." Created <br />";
		echo "------------------- <br />";
	}

?>