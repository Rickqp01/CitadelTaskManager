<?php
/**
* Logout/Session Destroy
*
*/
session_start();
session_unset();
session_destroy();
session_regenerate_id(true);
echo '<script type="text/javascript">window.location="login.php"</script>';