<?php 
	// Gets the s parameter or sets the sub as "training" (Default)
	$sub = GetSubItem("training");
?>

<ul class="nav nav-pills">
	<li class="<?=($sub == "training" ? "active" : "")?>"><a href="index.php?p=team&s=training">Training</a></li>
	<li class="<?=($sub == "players" ? "active" : "")?>"><a href="index.php?p=team&s=players">Players</a></li>
	<li class="<?=($sub == "positions" ? "active" : "")?>"><a href="index.php?p=team&s=positions">Positions</a></li>
	<li class="<?=($sub == "staff" ? "active" : "")?>"><a href="index.php?p=team&s=staff">Staff</a></li>
</ul>

<div class="row-fluid">
	
	<?php 
		switch($sub) {
          // Private - need login
          case "training"  : include("components/private/team/team_training.php"); break;
          case "players"   : include("components/private/team/team_players.php"); break;
          case "positions" : include("components/private/team/team_positions.php"); break;
          case "staff"     : include("components/private/team/team_staff.php"); break;     
          // Error - 404 page not found
          default          : include("components/error/404.php"); break;
        }
	?>
</div>