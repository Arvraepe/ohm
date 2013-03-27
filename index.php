<?php
  require("general.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Template &middot; Online Handball</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/oh.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="container">

      <div class="masthead">
        <h3 class="muted">
          Online Handball 
          <?php if ($page_type == "private") { ?>
          <small style="vertical-align: text-bottom;" class="pull-right">
            <div class="btn-group">
              <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-user icon-white"></i>
                <?=$_SESSION['username']?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="index.php?p=settings">Settings</a></li>
                <li class="divider"></li>
                <li><a href="scripts/scr_logout.php">Logout</a></li>
              </ul>
            </div>
          </small>
          <?php } ?>
        </h3>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <ul class="nav">
                <?php if ($page_type == "private") { ?>
                  <li class="<?=($page == "dashboard" ? "active" : "")?>"><a href="index.php?p=dashboard">Dashboard</a></li>
                  <li class="<?=($page == "league" ? "active" : "")?>"><a href="index.php?p=league">League</a></li>
                  <li class="<?=($page == "team" ? "active" : "")?>"><a href="index.php?p=team">Team</a></li>
                  <li class="<?=($page == "transfer" ? "active" : "")?>"><a href="index.php?p=transfer">Transfer</a></li>
                  <li class="<?=($page == "finance" ? "active" : "")?>"><a href="index.php?p=finance">Finance</a></li>
                <?php } else if ($page_type == "public") { ?>
                  <li class="<?=($page == "home" ? "active" : "")?>"><a href="index.php?p=home">Home</a></li>
                  <li class="<?=($page == "tour" ? "active" : "")?>"><a href="index.php?p=tour">Tour</a></li>
                  <li class="<?=($page == "login" ? "active" : "")?>"><a href="index.php?p=login">Login</a></li>
                  <li class="<?=($page == "register" || $page == "registred" ? "active" : "")?>"><a href="index.php?p=register">Register</a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php

        switch($page) {
          // Private - need login
          case "dashboard"  : include("components/private/dashboard.php"); break;
          case "players"    : include("components/private/players.php"); break;
          case "finance"    : include("components/private/finance.php"); break;
          case "league"     : include("components/private/league.php"); break;
          case "team"       : include("components/private/team.php"); break;
          // Public - no login needed
          case "login"      : include("components/public/login.php"); break;
          case "register"   : include("components/public/registration.php"); break;
          case "registred" : include("components/public/registred.php"); break;
          // Error - 404 page not found
          default           : include("components/error/404.php"); break;
        }

      ?>

      <hr>

      <div class="footer">
        <p>&copy; Company 2013</p>
      </div>

    </div>
  </body>
</html>
<?php $db->close(); ?>
