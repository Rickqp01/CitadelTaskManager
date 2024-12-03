<?php

// If the user is already logged in:
if(isset($_SESSION['x_user']['uid'])){
	echo '<script type="text/javascript">window.location="dashboard.php"</script>'; exit();
}else{

    # send to login page
    echo '<script type="text/javascript">window.location="login.php"</script>'; exit();
}