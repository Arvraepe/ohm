<?php  

    session_start();

    /*


                    Controlling the pages and making sure nobody sees things he don't want/needs to see


    */
    // Using general in scripts need to set the $skip_page_control so 
    // general skips this possible redirect and the SQL features and functions can be used
    if(!isset($skip_page_control)) {
        // Get the Current page 
        $page = "home";
        $page_type = "public";

        if(isset($_GET['p'])){
            $page = $_GET['p'];
        } else if(isset($_SESSION['uid'])) {
            // When no page is set but the user is logged in -> go to dashboard instead of home
            $page = "dashboard";
        }

        // Login Required for this page?
        $public = array("home", "login", "register", "tour", "registred");
        if (!in_array($page, $public)) {
            $page_type = "private";

            // Session is needed to be set for this page ... but is it?
            if (!isset($_SESSION['uid'])) header("location: index.php?p=login&redirect=".$page);
        } else {
            // Logged in user tries to access public page
            if (isset($_SESSION['uid'])) header("location: index.php");
            // Register with a running activation
            else if ($page == "register" && isset($_SESSION['activated'])) $page = "registred";
            // Register with no activation
            else if ($page == "register" && !isset($_SESSION['activated'])) $page = "register";
            // Registred without the $_SESSION var
            else if ($page == "registred" && !isset($_SESSION['activated'])) $page = "register";
        }
    }

    function GetSubItem($default){
        return isset($_GET['s']) ? $_GET['s'] : $default;
    }


    /*


                        STARTING GENERAL


    */
    $db = new mysqli("localhost", "root", "", "handball");

    // -------------- QUERYING HELPERS
    function QUERY($sql){
        global $db;

        $result = $db->query($sql) or die ($db->error);
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

    function FETCH_ALL_OR_NULL_SQL($sql){
        $r = QUERY($sql);

        if(count($r) > 0)
            return $r;
        
        return NULL;
    }

    function FETCH_OR_NULL_SQL($sql){
        $r = QUERY($sql);
        
        if(count($r) > 0)
            return $r[0];
        
        return NULL;    
    }

    function FETCH_OR_NULL($table, $field, $value){
        $r = QUERY("SELECT * FROM `".$table."` WHERE ".$field." = '".mysql_escape_string($value)."'");
        
        if(count($r) > 0)
            return $r[0];
        
        return NULL;
    }

    function FETCH_ALL_OR_NULL($table, $field, $value){
        $r = QUERY("SELECT * FROM `".$table."` WHERE ".$field." = '".mysql_escape_string($value)."'");
        
        if(count($r) > 0)
            return $r;
        
        return NULL;
    }

    // -------------- END QUERYING HELPERS

    function GetPlayerAge($birthday) {
        $cyear = date("Y");
        $cmonth = date("m");
        $cday = date("d");

        $bdate = explode("-", $birthday);
        $age = $cyear - $bdate[0];

        if ($bdate[1] > $cmonth || ($bdate[1] == $cmonth && $bdate[2] > $cday))
            $age -= 1;

        return $age; 
    }

    function GetTalentColorClass($talent){
        $class = "";
        switch ($talent) {
            case 1: $class = "important"; break;
            case 2: $class = "warning"; break;
            case 3: $class = "info"; break;
            case 4: $class = "success"; break;
            case 5: $class = "success"; break;
        }
        return $class;
    }

    function GetExperienceColorClass($exp){
        $class = "";
        switch ($exp) {
            case 0: $class = "important"; break;
            case 1: $class = "warning"; break;
            case 2: $class = "info"; break;
            case 3: $class = "success"; break;
        }
        return $class;
    }

    // ----- PERSISTENCE GETTERS --------

    function GetUserById($uid){
        return FETCH_OR_NULL("user", "id", $uid);
    }

    function GetUserByUsername($username){
        return FETCH_OR_NULL("user", "username", $username);
    }

    function GetUserByEmail($email){
        return FETCH_OR_NULL("user", "email", $email);
    }

    function GetPlayerById($pid){
       return FETCH_OR_NULL("player", "id", $pid);
    }

    function GetPlayersByTeam($tid){
        return FETCH_ALL_OR_NULL("player", "tid", $tid);
    }

    function GetTeamById($teamid){
        return FETCH_OR_NULL("team", "id", $teamid);
    }

    function GetDivisionById($did){
        return FETCH_OR_NULL("division", "id", $did);       
    }

    function GetTeamsByDivision($did){
        return FETCH_ALL_OR_NULL("team", "did", $did);       
    }

    function GetStatusById($sid){
        return FETCH_OR_NULL("player_status", "id", $sid);
    }

    function GetTransactionsByTeamId($tid, $amount){
        return FETCH_ALL_OR_NULL_SQL("SELECT * FROM `transaction` WHERE tid = ".$tid." ORDER BY `when` DESC LIMIT ".$amount);
    }

    // ----- END PERSISTENCE GETTERS --------

    function GetPlayerStatus($player){
        if($player->status != 0){
            $status = GetStatusById($player->status);
            return '<div class="img-player-info label label-'.$status->class.'">'.$status->text.'</div>';
        }
        return "";

    }


    function RestoreSession($uid){
        // TODO
        //$user = GetUserById($uid);
    }

    function SetSessionVars($user){
        $_SESSION['uid'] = $user->id;
        $_SESSION['tid'] = $user->tid;
        $_SESSION['username'] = $user->username;

        $team = GetTeamById($user->tid);
        $_SESSION['did'] = $team->did;
    }

    function RandomHash() {
        $stack = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $length = 40;

        $str = "";
        for ($i=0; $i < $length; $i++) { 
            $in = rand(0, strlen($stack)-1);
            $str .= $stack[$in];    
        }

        return $str;
    }

?>