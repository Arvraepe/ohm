<?php

	if(isset($_GET['fire'])){
		$sid = $_GET['fire'];
		if(FireStaff($sid, $_SESSION['tid'])){
			?>
				<div class="alert alert-success">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Success!</strong> You successfully deleted a staff member!
				</div>
			<?php
		}
	}

?>
<script type="text/javascript">
	function sure(id){	
		$("#hire"+id).fadeOut(0);
		$("#sure"+id).fadeIn(0);
		$("#no"+id).fadeIn(0);
	}
	function no(id){
		$("#no"+id).fadeOut(0);
		$("#sure"+id).fadeOut(0);
		$("#hire"+id).fadeIn(0);
	}
</script>
<?php 
	$staff = GetStaffByTeamId($_SESSION['tid']);

	if($staff != NULL){
	foreach ($staff as $s) {

	$stars = "";
	for ($i=0; $i < ($s->experience/1000); $i++) { 
		$stars .= '<img src="img/icons/star.png">';
	}
?>
<div class="row-fluid well">
	<div class="span1">
		<img width="40" src="img/playerback.png" />
	</div>
	<div class="span3">
		<h4><?=$s->name?></h4>
	</div>
	<div class="span2">
		<h4><?=$s->type?></h4>
	</div>
	<div class="span1">
		<h4><img src="img/icons/money_euro.png" /><?=$s->salary?></h4>
	</div>
	<div class="span3">
		<h4><?=$stars?></h4>
	</div>
	<div class="span2" style="text-align: right;"> 
		<a id="hire<?=$s->id?>" class="btn btn-danger btn-large" href="javascript: void(0);" onclick="sure(<?=$s->id?>);">fire (&euro;200)</a>
		<a style="display: none;" id="sure<?=$s->id?>" class="btn btn-success btn-large" href="index.php?p=team&s=staff&fire=<?=$s->id?>">sure?</a>
		<a style="display: none;" id="no<?=$s->id?>" class="btn btn-danger btn-small" href="javascript: void(0);" onclick="no(<?=$s->id?>)">no</a>
	</div>
</div>
<?php } } else { ?>

	<h2>You don't have any staff members</h2>

<?php } ?>