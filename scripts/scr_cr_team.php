<?php

	$db = new mysqli("localhost", "root", "", "handball");

	function QUERY($sql){
		global $db;

		$result = $db->query($sql);
		if($result){
		    while ($row = $result->fetch_object()){
		        $a[] = $row;
		   	}

		    // Free result set
		    $result->close();
		    $db->next_result();

		    return $a;
		}
	}

	function GetBirthDate($age){
		$year = date("Y") - $age;
		$month = rand(1,12);
		$day = rand(1,28);

		if  (	$month > date("m") 
				|| ($month == date("m") && $day > date("d"))
			){

			$year -= 1;
		}

		echo("Birthday: ".$year."-".$month."-".$day." <br />");
		return $year."-".$month."-".$day;
	}

	function GetRandomName($amount){

		$lastnames = QUERY("SELECT * FROM `lastnames`");
		$firstnames = QUERY("SELECT * FROM `firstnames`");

		for ($i=0; $i < $amount; $i++) { 
			$lrid = rand(0, count($lastnames));
			$frid = rand(0, count($firstnames));
			$names[$i] = $firstnames[$frid]->firstname." ".$lastnames[$lrid]->lastname;
		}

		return $names;

	}

	// For one player based upon his age
	function GetRandomStats($age, $talent, $keeper){
		echo("Age: ".$age.", Talent: ".$talent." Keeper: ".$keeper." <br />");

		// Age may vary between 16 and 18 for a new created team
		switch ($age) {
			case 16: $start = 10; $end = 15+2*$talent; break;
			case 17: $start = 14; $end = 19+2*$talent; break;
			case 18: $start = 18; $end = 23+2*$talent; break;
		}

		$correcter = 0;
		if($keeper == 1){
			$correcter = ceil($start / 4);
		}

		$skills->strength = rand($start, $end-$correcter);
		$skills->speed = rand($start, $end);
		$skills->intelligence = rand($start, $end-$correcter);
		$skills->accuracy = rand($start, $end-$correcter);
		$skills->teamplay = rand($start, $end);
		$skills->reflexes = rand($start+$correcter, $end+($correcter * 3));

		echo("Strength: ".$skills->strength." <br />");
		echo("Speed: ".$skills->speed." <br />");
		echo("intelligence: ".$skills->intelligence." <br />");
		echo("accuracy: ".$skills->accuracy." <br />");
		echo("teamplay: ".$skills->teamplay." <br />");
		echo("reflexes: ".$skills->reflexes." <br />");

		return $skills;

	}

	$userid = 1;

	// Every new team gets 12 players
	$names 		= GetRandomName(12);
	// 6x 16y | 4x 17y | 2x 18y
	$ages 		= array(16,16,16,16,16,16,17,17,17,17,18,18);
	// 3x 1t | 4x 2t | 3x 3t | 1x 4t | 1x 5t
	$talents 	= array(1,1,1,2,2,2,2,3,3,3,4,5); 
	//nationalities
	$nationalities = array("be","be","be","be","be","be","be","be","be","be","be","be","be","be","be","be","nl","fr","de","dk","se","be","nl","fr","de","dk","se","nl","fr","de","dk","se","be","nl","fr","de","dk","se","es","pl","ro","ru","kr","jp");

	shuffle($ages); shuffle($talents); shuffle($nationalities);

	for ($i=0; $i < 12; $i++) { 
		// first two are keepers
		$keeper = 0;
		if($i < 2)
			$keeper = 1;

		$skills = GetRandomStats($ages[$i], $talents[$i], $keeper); 
		$birthdate = GetBirthDate($ages[$i]);
		$in = rand(0, count($nationalities)-1);
		$nat = $nationalities[$in];
		
		$handed = 'r';
		if(rand(0,4) == 1){
			$handed = 'l';
		}

		echo("nationality: ".$nat." index: ".$in."<br />");
		echo("<br /><br />");

		// $db->query("INSERT INTO `player` VALUES(
		// 		NULL,
		// 		'".$names[$i]."',
		// 		'".$birthdate."',
		// 		'".$nat."',
		// 		'".$handed."',
		// 		'".$keeper."',
		// 		".$skills->strength.",
		// 		".$skills->speed.",
		// 		".$skills->accuracy.",
		// 		".$skills->teamplay.",
		// 		".$skills->intelligence.",
		// 		".$skills->reflexes.",
		// 		0,
		// 		".$talents[$i].",
		// 		0
		// 	)") or die($db->error);

		// $last_id = $db->insert_id;
		// $db->query("INSERT INTO `user_player` VALUES(".$userid.", ".$last_id.")");

	}

	$db->close();
?>