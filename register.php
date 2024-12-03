<?php
$session_no_chk = 1;

require_once($_SERVER["DOCUMENT_ROOT"].'/ado/core.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CN Admin - Register</title>

    <?php require_once($_SERVER["DOCUMENT_ROOT"].'/ado/css.php'); ?>
</head>
<body class="bg-gradient-primary">

    <div class="container">

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
                                <h1 class="h4 text-gray-900 mb-4">Create Account</h1>
                            </div>
                            <form id="registerform" method="post" action="?">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="FirstName" name="FirstName"
                                            placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="LastName" name="LastName"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="InputEmail" name="InputEmail"
                                        placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="InputPassword" name ="InputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="RepeatPassword" name="RepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user" id="inviteCode" name="inviteCode" type="text" placeholder="Invite Code" />
                                </div>
                                <button id="submit_btn" type="submit" class="btn btn-primary btn-user btn-block">Submit <i class="fas fa-sign-in-alt"></i></button>
                                <input id="index_register_page_submit" type="hidden" name="index_register_page_submit" value="1">
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Go to Login</a>
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
        $( "#registerform" ).submit(function(event) { 
           
            // get the data
            var formData = $('#registerform').serialize();

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
                    <?php echo fn_toastr_success('Success', 'Action completed. Redirecting to login page ...'); ?>
                    setTimeout(function() {
                    window.location.href = "login.php";
                    }, 3000);
                    return false;
                } else if (data == 83) {
                    <?php echo fn_toastr_error('Error', 'Invalid email address'); ?>
                } else if (data == 85) {
                    <?php echo fn_toastr_error('Error', 'Email already in use'); ?>
                } else if (data == 75) {
                    <?php echo fn_toastr_error('Error', 'Passwords do not match'); ?>
                } else if (data == 65) {
                    <?php echo fn_toastr_error('Error', 'Invalid invite code'); ?>
                } else if (data == 55) {
                    <?php echo fn_toastr_error('Error', 'First or last name was empty'); ?>
                } else if (data == 45) {
                    <?php echo fn_toastr_error('Error', 'One or more passwords empty'); ?>
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