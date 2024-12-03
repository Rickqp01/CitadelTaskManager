<?php
# start the PHP session
session_start();

# require server settings
require_once($_SERVER["DOCUMENT_ROOT"].'/config.php');

# require library
require_once($_SERVER["DOCUMENT_ROOT"].'/ado/library.php');

# check login session
if(!isset($session_no_chk)){

	if(!isset($_SESSION['x_user']['uid'])){
		syslog(LOG_INFO|LOG_MAIL, "4822: user session error");
		echo '<script type="text/javascript">window.location="logout.php"</script>'; exit();
	}

    $x_user           = array();
    $x_user['email']  = $_SESSION['x_user']['email'];
    $x_user['uid']    = $_SESSION['x_user']['uid']; 
}

# connect to database
$mysql_pdo = fn_load_mysql_pdo();
