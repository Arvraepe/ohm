<div class="jumbotron hero-unit">
<div class="lead">Latest game played resulted in</div>
	<table width="100%">
		<tr>
			<td style="text-align: right;"><strong>HC Luxor Eeklo</strong></td>
			<td>vs</td>
			<td style="text-align: left;"><strong>Knack Roeselare</strong></td>
		</tr>
		<tr>
			<td style="text-align: right;" class="won">24</td>
			<td> - </td>
			<td style="text-align: left;">15</td>
		</tr>
	</table>
	<div style="text-align: right;"><a class="btn btn-info" href="#">View Game</a></div>
</div>
<?php 
$division = GetDivisionById($_SESSION['did']); ?>
<h1><?=$division->name?> <small> <a class="btn btn-h1 btn-primary" href="">Other divisions</a></small></h1>
<table class="table">
	<tr>
		<th>#</th>
		<th>Team</th>
		<th>Played</th>
		<th>W</th>
		<th>L</th>
		<th>D</th>
		<th>+</th>
		<th>-</th>
		<th>Points</th>
	</tr>
	<?php 
		$divteams = GetTeamsByDivision($_SESSION['did']);

		$c = 0;
		$classes = array("success", "info", "", "", "", "", "", "", "warning", "error");
		foreach ($divteams as $team) {
			//echo("test: ".$team[0]."<br />");
			?>
			<tr <?php if($classes[$i] != "") echo('class="'.$classes[$i].'"') ?>>
				<td><?=($c+1)?></td>
				<td><?=$team->name?></td>
				<td><?=$team->played?></td>
				<td><?=$team->won?></td>
				<td><?=$team->lost?></td>
				<td><?=$team->draw?></td>
				<td><?=$team->plus?></td>
				<td><?=$team->minus?></td>
				<td><?=$team->points?></td>
			</tr>
			<?php
			$c++;
		}
	?>
</table>