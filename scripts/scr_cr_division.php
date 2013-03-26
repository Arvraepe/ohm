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

		return $skills;

	}

	function create_players($teamid) {
		global $db;

		$player_amount = 12;

		// Every new team gets 12 players
		$names 		= GetRandomName($player_amount);
		// 6x 16y | 4x 17y | 2x 18y
		$ages 		= array(16,16,16,16,16,16,17,17,17,17,18,18);
		// 3x 1t | 4x 2t | 3x 3t | 1x 4t | 1x 5t
		$talents 	= array(1,1,1,2,2,2,2,3,3,3,4,5); 
		//nationalities
		$nationalities = array("be","be","be","be","be","be","be","be","be","be","be","be","be","be","be","be","nl","fr","de","dk","se","be","nl","fr","de","dk","se","nl","fr","de","dk","se","be","nl","fr","de","dk","se","es","pl","ro","ru","kr","jp");

		shuffle($ages); shuffle($talents); shuffle($nationalities);

		for ($i=0; $i < $player_amount; $i++) { 
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
			$keepdb = 'n';
			if ($keeper == 1)
				$keepdb = 'y';

			// Starting salary
			$salary = 550;
		    $salary += $skills->strength + $skills->accuracy + $skills->intelligence + $skills->reflexes + $skills->teamplay + $skills->speed;
		    $salary += $talents[$i] * 50;

			$result = $db->query("INSERT INTO `player` 
				(
					tid,
					name,
					birthdate,
					salary,
					nationality,
					handed,
					keeper,
					strength,
					speed,
					accuracy,
					teamplay,
					intelligence,
					reflexes,
					experience,
					talent,
					status
				) 
				VALUES(
					".$teamid.",
					'".$names[$i]."',
					'".$birthdate."',
					'".$salary."',
					'".$nat."',
					'".$handed."',
					'".$keepdb."',
					".$skills->strength.",
					".$skills->speed.",
					".$skills->accuracy.",
					".$skills->teamplay.",
					".$skills->intelligence.",
					".$skills->reflexes.",
					0,
					".$talents[$i].",
					0
				)") or die($db->error);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Created [player] > ".$names[$i]." <br />";
		}
	}

	function create_teams($amount, $did){
		global $db;

		$teams = QUERY("SELECT count(*) as amount FROM `team`");
		for ($i=0; $i < $amount; $i++) { 
			$name = "HC D".$did."-T".($teams[0]->amount+$i+rand(100,900));
			$db->query("INSERT INTO `team` (name, did) VALUES ('".$name."', ".$did.")");
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Created [team] > ".$name."<br />";
			create_players($db->insert_id);
		}
	}

	// Creates a division => Division + ( count of divisions + 1 )
	function create_division(){
		global $db;

		$divs = QUERY("SELECT count(*) as amount FROM `division`");
		$divname = "Division ".($divs[0]->amount+1);

		$db->query("INSERT INTO `division` (name) VALUES ('".$divname."')");
		echo "Created [division] > ".$divname."<br />";
		create_teams(10, $db->insert_id, $divname);
	}


	// SCRIPT
	// Create an extra division
	create_division();

	$db->close();
?>