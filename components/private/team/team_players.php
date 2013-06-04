
<?php

  $players = GetPlayersByTeam($_SESSION['tid']);

if($players != NULL){

  foreach ($players as $player) {
    $age = $player->age;

    $talentColor = GetTalentColorClass($player->talent);
    $experienceColor = GetExperienceColorClass($player->experience);
    
    $handed = "Right";
    if($player->handed == 'l')
      $handed = "Left";

    $keeper = "";
    if($player->keeper == 'y')
      $keeper = "Keeper";

    // Special info? (Selected, Injured, Birthday, etc)
    // Example: <span class="label label-info img-player-info">selected</span>
    $ornament = GetPlayerStatus($player);
?>
<script type="text/javascript">
  function toggle(id){
    if($("#player-extra-"+id).css("display") == "none"){
      $("#player-extra-"+id).show(200);
    } else {
      $("#player-extra-"+id).hide(200);
    }
  }
</script>
<div class="row-fluid player" onclick="toggle(<?=$player->id?>);">
    <div class="span2">
      <div id="player_picture" class="img-player">
        <img class="img-rounded" width="142" height="142" src="http://i2.wp.com/c0589922.cdn.cloudfiles.rackspacecloud.com/avatars/male200.png" />
        <?=$ornament?>
      </div>
    </div>

    <div class="span4 table-player">
      <table>
        <tr>
          <td width="40%" class="table-key">Name</td>
          <td width="60%" class="table-value"><?=$player->name?></td>
        </tr>
        <tr>
          <td class="table-key">Age</td>
          <td class="table-value"><?=$age?></td>
        </tr>
        <tr>
          <td class="table-key">Nationality</td>
          <td class="table-value"><img class="img-inline" src="img/flags/<?=$player->nationality?>.png" /></td>
        </tr>
        <tr>
          <td class="table-key">Experience</td>
          <td class="table-value"><span class="badge badge-<?=$experienceColor?>"><?=($player->experience)?></span></td>
        </tr>
        <tr>
          <td class="table-key">Talent</td>
          <td class="table-value"><span class="badge badge-<?=$talentColor?>"><?=($player->talent)?></span></td>
        </tr>
        <tr>
          <td class="table-key">Primary Hand</td>
          <td class="table-value"><?=$handed?></td>
          <td><strong><?=$keeper?></strong></td>
        </tr>
      </table> 
    </div>
    <div class="span6 table-player" style="position: relative;">
      <table>
        <tr>
          <td class="table-key">Strength</td>
          <td class="table-value"><div title="<?=($player->strength)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->strength*3)?>px;"></div></div></td>
        </tr>
        <tr>
          <td class="table-key">Speed</td>
          <td class="table-value"><div title="<?=($player->speed)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->speed*3)?>px;"></div></div></td>
        </tr>
        <tr>
          <td class="table-key">Accuracy</td>
          <td class="table-value"><div title="<?=($player->accuracy)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->accuracy*3)?>px;"></div></div></td>
        </tr>
        <tr>
          <td class="table-key">Teamplay</td>
          <td class="table-value"><div title="<?=($player->teamplay)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->teamplay*3)?>px;"></div></div></td>
        </tr>
        <tr>
          <td class="table-key">Intelligence</td>
          <td class="table-value"><div title="<?=($player->intelligence)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->intelligence*3)?>px;"></div></div></td>
        </tr>
        <tr>
          <td class="table-key">Reflexes</td>
          <td class="table-value"><div title="<?=($player->reflexes)?>" class="skill-container"><div class="skill-fill" style="width: <?=($player->reflexes*3)?>px;"></div></div></td>
        </tr>
      </table>   
    </div>
</div>
<div id="player-extra-<?=$player->id?>" class="row-fluid player-extra-info">
  <div class="row-fluid">
      <div class="span6">
        <h3>Statistics</h3>
        <p>Scored <span class="label label-info">87</span> goals this <strong>season</strong> and <span class="label label-info">348</span> while playing in your <strong>team</strong>.</p>
        <p>Received <span class="label label-warning">6</span> <strong>yellow</strong> and <span class="label label-important">2</span> <strong>red</strong> cards this season.</p>
      </div>
      <div class="span4">
        <h3>Info</h3>
        <p>This player has a salary of <img src="img/icons/money_euro.png"><span class="label label-warning"><?=$player->salary?></span> a month.</p>
        <p>His birthday is on day <strong><?=$player->birthdate?></strong> of the season.
      </div>
      <div class="span2">
        <p><a class="btn btn-warning fullwidth" href="">Put on transferlist</a></p>
        <p><a class="btn btn-danger fullwidth" href="">Fire</a></p>
      </div>
  </div>
</div>

<?php
  }
} else { // Playerslist is empty
?>

<h3>No Players found</h3>

<?php
}
?>