<?php  

    session_start();

    // Using general in scripts need to set the $skip_page_control so 
    // general skips this possible redirect and the SQL features and functions can be used
    if(!isset($skip_page_control)) {
        // Get the Current page 
        $page = "home";
        $page_type = "public";
        if(isset($_GET['p'])){
            $page = $_GET['p'];
        } else if(isset($_SESSION['uid'])) {
            $page = "dashboard";
        }

        // Login Required for this page?
        $public = array("home", "login", "register", "tour");
        if (!in_array($page, $public)) {
            $page_type = "private";
            // Session is needed to be set for this page ... but is it?
            if (!isset($_SESSION['uid'])) {
                // Session isn't set so we need to redirect to login page
                header("location: index.php?p=login&redirect=".$page);
            }
        } else {
            if (isset($_SESSION['uid'])){           
                // logged in tries to access page like login or registration... not for his eyes!
                header("location: index.php");
            }
        }
    }

    $db = new mysqli("localhost", "root", "", "handball");

    function QUERY($sql){
        global $db;

        $result = $db->query($sql);
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

    // Only for own use
    function FETCHORNULL($table, $field, $value){
        $r = QUERY("SELECT * FROM `".$table."` WHERE ".$field." = '".mysql_escape_string($value)."'");
        
        if(count($r) > 0)
            return $r[0];
        
        return NULL;
    }

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

    function GetUserById($uid){
        return FETCHORNULL("user", "id", $uid);
    }

    function GetUserByUsername($username){
        return FETCHORNULL("user", "username", $username);
    }

    function GetPlayerById($pid){
       return FETCHORNULL("player", "id", $pid);
    }

    function SearchTeamById($teamid){
        return FETCHORNULL("team", "id", $teamid);
    }

    function GetDivisionById($did){
        return FETCHORNULL("division", "id", $did);       
    }

    function GetTeamsByDivision($did){
        return FETCHORNULL("team", "did", $did);       
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