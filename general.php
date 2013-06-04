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

    $ohm = FETCH_OR_NULL_SQL("SELECT * FROM `ohm`");

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

    // ----- Get Functions --------

    function GetUserById($uid){
        return FETCH_OR_NULL("users", "id", $uid);
    }

    function GetUserByUsername($username){
        return FETCH_OR_NULL("users", "username", $username);
    }

    function GetUserByEmail($email){
        return FETCH_OR_NULL("users", "email", $email);
    }

    function GetPlayerById($pid){
       return FETCH_OR_NULL("players", "id", $pid);
    }

    function GetPlayersByTeam($tid){
        return FETCH_ALL_OR_NULL("players", "tid", $tid);
    }

    function GetTeamById($teamid){
        return FETCH_OR_NULL("team", "id", $teamid);
    }

    function GetDivisionById($did){
        return FETCH_OR_NULL("divisions", "id", $did);       
    }

    function GetTeamsByDivision($did){
        return FETCH_ALL_OR_NULL("team", "did", $did);       
    }

    function GetStatusById($sid){
        return FETCH_OR_NULL("player_status", "id", $sid);
    }

    function GetTransactionsByTeamId($tid, $amount){
        return FETCH_ALL_OR_NULL_SQL("SELECT * FROM `transactions` WHERE tid = ".$tid." AND completed = 'y' ORDER BY `when` DESC LIMIT ".$amount);
    }

    function GetStaffOnList(){
        return FETCH_ALL_OR_NULL_SQL("SELECT id, tid, name, salary, experience, typename as type FROM `staff`, `staff_type` WHERE staff.type = staff_type.typeid AND staff.tid is null");
    }

    function GetStaffByTeamId($tid){
        return FETCH_ALL_OR_NULL_SQL("SELECT id, tid, name, salary, experience, typename as type FROM `staff`, `staff_type` WHERE staff.type = staff_type.typeid AND staff.tid = ".$tid);
    }

    function GetStaffById($sid){
        return FETCH_OR_NULL("staff", "id", $sid);
    }

    // ----- END Get Functions --------

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

    /*
            
            Creation functions

    */
    function CreateStaff($name, $experience, $salary, $type){
        global $db;
        $db->query("INSERT INTO `staff` (name, salary, experience, type) VALUES (
                '".$name."',
                ".$salary.",
                ".$experience.",
                ".$type."
            )");
        return $db->insert_id;
    }

    function CreateTransaction($amount, $message, $when, $extra, $class){
        $db->query("INSERT INTO `transactions` (when, amount, completed, message, extra, class) VALUES (
                '".$when."',
                ".$amount.",
                'n',
                '".$message."',
                '".$extra."',
                '".$class."'
            )");
        return true;
    }

    function HireStaff($staffid, $teamid){
        global $db;
        $staff = GetStaffById($staffid);
        if($staff != NULL && $staff->tid == NULL){
            $db->query("UPDATE `staff` SET tid = ".$teamid." WHERE id = ".$staffid) or die ($db->error);
            return true;
        } 

        return false;
    }

    function FireStaff($staffid, $teamid){
        global $db;
        $staff = GetStaffById($staffid);
        if($staff != NULL && $staff->tid == $teamid){
            // Fire the staff member
            $db->query("UPDATE `staff` SET tid = NULL WHERE id = ".$staffid);
            // Add the transaction fire fee.
        }
    }

?>