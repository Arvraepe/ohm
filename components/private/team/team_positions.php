<div class="span4 boxed">
	<h4>Base</h4>
	<?php
		$players = QUERY("SELECT * FROM `players` WHERE tid = ".$_SESSION['tid']." ORDER BY birthdate ASC LIMIT 7");
		  
		  foreach ($players as $player) {
	?>
  <div id="player_picture" class="img-player player-thumb" style="text-align: left;">
    <img width="30" style="vertical-align: middle;" class="img-rounded" src="http://i2.wp.com/c0589922.cdn.cloudfiles.rackspacecloud.com/avatars/male200.png" /> 
    <strong class="pushRight10"><?=$player->name?></strong>
  </div>
  <?php
  	}
  ?>
  <h4>Bench</h4>
</div>
<div class="span8 boxed">Field</div>