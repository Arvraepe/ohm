<?php 
	// Do actions before anything else.. because they could influence the results
	if(isset($_GET['hire'])){
		$sid = $_GET['hire'];
		if(HireStaff($sid, $_SESSION['tid'])){
			?>
				<div class="alert alert-success">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Success!</strong> You successfully hired a staff member!
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
<?
	$staff = GetStaffOnList();
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
		<a id="hire<?=$s->id?>" class="btn btn-primary btn-large" href="javascript: void(0);" onclick="sure(<?=$s->id?>);">hire</a>
		<a style="display: none;" id="sure<?=$s->id?>" class="btn btn-success btn-large" href="index.php?p=transfer&s=staff&hire=<?=$s->id?>">sure?</a>
		<a style="display: none;" id="no<?=$s->id?>" class="btn btn-danger btn-small" href="javascript: void(0);" onclick="no(<?=$s->id?>)">no</a>
	</div>
</div>
<?php } ?>