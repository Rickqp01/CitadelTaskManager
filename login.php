<?php
$session_no_chk = 1;

# load core checks and settings
require_once($_SERVER["DOCUMENT_ROOT"].'/ado/core.php');

# If the user is already logged in:
if(isset($_SESSION['x_user']['uid'])){
	echo '<script type="text/javascript">window.location="dashboard.php"</script>'; exit();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Citadel Nerds</title>

        <?php require_once($_SERVER["DOCUMENT_ROOT"].'/ado/css.php'); ?>

    </head>
    <body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-flex justify-content-center flex-nowrap my-auto">
                            <img src="assets/img/el_cid.png" alt="The Citadel" style="width:300px;height:300px;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Project Login</h1>
                                    </div>
                                    <form id="loginform" method="post" action="?">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                             id="username" name="login_name" aria-describedby="emailHelp"
                                                placeholder="User name or email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                            id="password" name="login_passwd" placeholder="Password">
                                        </div>
                                        <button id="submit_btn" type="submit" class="btn btn-primary btn-user btn-block">Login <i class="fas fa-sign-in-alt"></i></button>
                                        <input id="index_login_page_submit" type="hidden" name="index_login_page_submit" value="1">
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create Account</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
        
        <?php require_once($_SERVER["DOCUMENT_ROOT"].'/ado/javascript.php'); ?>

        <script type="text/javascript">
        // login form trigger
        $( "#loginform" ).submit(function(event) { 
           
            // get the data
            var formData = $('#loginform').serialize();

            // process the form
            $.ajax({
                type        : 'POST', 				// define the type of HTTP verb we want to use (POST for our form)
                url         : '/ado/process.php', 	// the url where we want to POST
                data        : formData, 			// our data object
                dataType    : 'json', 				// what type of data do we expect back from the server
                encode      : true
            })

            // using the done promise callback
            .done(function(data) {
                // create a growl based on the return
                if (data == 1) {
                    <?php echo fn_toastr_error('Error', 'Processing error'); ?>
                } else if (data == 0) {
                    window.location='dashboard.php';
                    return false;
                } else if (data == 78) {
                    <?php echo fn_toastr_error('Error', 'Empty email address'); ?>
                } else if (data == 79) {
                    <?php echo fn_toastr_error('Error', 'Empty password'); ?>
                } else if (data == 80) {
                    <?php echo fn_toastr_error('Error', 'Account disabled'); ?>
                } else if (data == 81) {
                    <?php echo fn_toastr_error('Error', 'Invalid credentials'); ?>
                }else{
                    // unknown response
                    <?php echo fn_toastr_error('Error', 'Processing error'); ?>
                }	
                
            });
                    
            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
        </script>

    </body>
</html>
