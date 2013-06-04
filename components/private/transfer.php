<?php 
	// Gets the s parameter or sets the sub as "training" (Default)
	$sub = GetSubItem("staff");
?>

<ul class="nav nav-pills">
	<li class="<?=($sub == "players" ? "active" : "")?>"><a href="index.php?p=transfer&s=players">Players</a></li>
	<li class="<?=($sub == "staff" ? "active" : "")?>"><a href="index.php?p=transfer&s=staff">Staff</a></li>
</ul>

<div class="row-fluid">
	
	<?php 
		switch($sub) {
          // Private - need login
          case "players"  : include("components/private/transfer/transfer_players.php"); break;
          case "staff"   : include("components/private/transfer/transfer_staff.php"); break;    
          // Error - 404 page not found
          default          : include("components/error/404.php"); break;
        }
	?>
</div>