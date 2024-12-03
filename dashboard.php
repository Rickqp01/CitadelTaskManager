<?php
# load core checks and settings
require_once($_SERVER["DOCUMENT_ROOT"].'/ado/core.php');

// actions
$link_menu   = '<a class="dropdown-item" tabindex="-1" href="#" data-toggle="modal" data-target="#add_modal_item">Add</a>';
$link_menu  .= '<a class="dropdown-item" tabindex="-1" href="#" data-toggle="modal" data-target="#del_modal_item">Delete</a>';

// actions button
$actions_btn = '<div class="dropdown">';
$actions_btn .= '<button type="button" class="btn btn-sm mb-trans-btn dropdown-toggle"';
$actions_btn .= ' id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">';
$actions_btn .= '<i class="fas fa-cog" style="margin-right: 10px;"></i>';
$actions_btn .= 'Actions';
$actions_btn .= '</button>';
$actions_btn .= '<div class="dropdown-menu mb-action-menu" aria-labelledby="dropdownMenu1">';
$actions_btn .= $link_menu;

// add item table
$table_1  = '<table class="table table-borderless" id="table_1">';

$input = '<input type="text" name="add_item_name" id="add_item_name" class="form-control form-control-sm" value="" placeholder="Task 1" >';
$table_1 .= '<tr><td><strong>Task Name</strong><br>'.$input.'</td></tr>';

$input = '<textarea class="form-control form-control-sm" rows="3" id="add_item_description" name="add_item_description" placeholder="My super awesome task"></textarea>';
$table_1 .= '<tr><td><strong>Description</strong>
			<br>'.$input.'</td></tr>';

$input = '<input type="text" name="add_item_assignment" id="add_item_assignment" class="form-control form-control-sm" value="" placeholder="Bob" >';
$table_1 .= '<tr><td><strong>Assigned To</strong><br>'.$input.'</td></tr>';

$input = '<input type="text" name="add_item_due_date" id="add_item_due_date" class="form-control form-control-sm" value="" placeholder="2024-12-31" >';
$table_1 .= '<tr><td><strong>Due Date</strong><br>'.$input.'</td></tr>';

$input = '<input type="text" name="add_item_status" id="add_item_status" class="form-control form-control-sm" value="" placeholder="In Progress">';
$table_1 .= '<tr><td><strong>Status</strong><br>'.$input.'</td></tr>';

$table_1 .= '</table>';

// edit item table
$table_3 = str_replace('table_1', 'table_3', $table_1);
$table_3 = str_replace('add_', 'edit_', $table_3);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CN Admin - Tasks</title>

    <?php require_once($_SERVER["DOCUMENT_ROOT"].'/ado/css.php'); ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-text mx-3">Citadel Nerds</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['x_user']['first_name'].' '.$_SESSION['x_user']['last_name']; ?>
                                </span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="register.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Add user
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
                        </div>
                        <div class="card-body">
                            <form autocomplete="off" name="dtForm" id="dtForm" action="javascript:void(0);" method="post">
                            <table class="table table-bordered" id="item_table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Task</th>
                                        <th>Assignment</th>
                                        <th>Status</th>
                                        <th>Start date</th>
                                        <th>Due date</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                            </form>
                        </div>
                        <div id="loadIMG"></div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Citadel Project 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">End your current session?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add -->
    <div class="modal fade" id="add_modal_item" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" >Add</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="" name="add_item_form" id="add_item_form" action="javascript:void(0);" method="post">
                        <div class="row row-sm">
                            <div class="col-sm-12 col-md-12 mb5">
                                <?php echo $table_1; ?>
                            </div>						
                            <input id="add_task_item" type="hidden" name="add_task_item" value="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="add_item_form_submit">Confirm</button>
                </div>
            </div>
        </div>
    </div><!-- modal -->

    <!-- Delete -->
    <div class="modal fade" id="del_modal_item" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" >Delete</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 text-center">
                        Delete this item?
                    </div>
                    <form autocomplete="off" class="" name="del_item_form" id="del_item_form" action="javascript:void(0);" method="post">
                        <input id="del_task_item" type="hidden" name="del_task_item" value="1">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="del_item_form_submit">Confirm</button>
                </div>
            </div>
        </div>
    </div><!-- modal -->

    <!-- Edit -->
    <div class="modal fade" id="edit_modal_item" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" >Edit</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="" name="edit_item_form" id="edit_item_form" action="javascript:void(0);" method="post">
                        <div class="row row-sm">

                            <div class="col-sm-12 col-md-12 mb5">
                                <?php echo $table_3; ?>
                            </div>
                            
                            <div id="edit_item_display_muted_id" class="col-md-12 text-right text-muted mt10"></div>
                        </div>
                        <input id="edit_task_item" type="hidden" name="edit_task_item" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="edit_item_form_submit">Confirm</button>
                </div>
            </div>
        </div>
    </div><!-- modal -->

<?php require_once($_SERVER["DOCUMENT_ROOT"].'/ado/javascript.php'); ?>

<!-- Datatable Build -->
<script type="text/javascript">

	$('#loadIMG').show();

	// datatables
	var oTable = $('#item_table').dataTable( {

		"bProcessing": false,
		"bServerSide": true,
		"bStateSave": true,
		"bSort": true,
		"autoWidth": true,
		"lengthMenu": [ 10, 15, 25, 50, 100, 200, 500, 1000 ],
		"sPaginationType": "full",
		"aoColumnDefs": [ 
			{ "className": 'control', "orderable": false, "targets": 0 },
			{ "sClass": "text-center", "aTargets": [ 0,6 ] },
			{ "bSortable": false, "aTargets": [0, 6] },
			{ "bSearchable": false, "aTargets": [0, 6] }
		],		
		"oLanguage": {
            "sProcessing": "Processing",
            "sSearch": "",
            "sInfo": "_START_ - _END_ / _TOTAL_",
            "sInfoFiltered": "Filtered Results",
            "sInfoEmpty": "No Match",
            "sLengthMenu": "_MENU_",
            "sEmptyTable": "Empty Table",
            "oPaginate": {
				"sPrevious": "Previous",
				"sFirst": "First",
				"sLast": "Last",
				"sNext": "Next",
			  }
        },
		"sAjaxSource": 'ado/process.php',
		"sServerMethod": "GET",
		"fnServerData": function ( sSource, aoData, fnCallback ) {
							/* Add some extra data to the sender */
							aoData.push( { "name": "task_list_request", "value": "1" } );
							$.getJSON( sSource, aoData, function (json) {
								/* Do whatever additional processing you want on the callback, then tell DataTables */
								fnCallback(json)
							} );
						},
		"iDisplayLength": 10,
		"sDom": '<"row" <"dtTopLeft col no-padding"><"dtTopRight col no-padding text-right"f>> <<"row dtMainTable"t>>  <"row" <"dtRelButton col-md-5 no-padding text-left"l><"dtInfo col-md-2 no-padding text-center"i><"col-md-5 text-right no-padding"p>> <"clear">',
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {

		},
   	    "fnDrawCallback": function( oSettings ) {
   	    	
		 	$('#loadIMG').hide();
             $('div.dtTopLeft').html('<?php echo $actions_btn; ?>');
		 	$("#item_table").show('fade');
		 	
		},
		"fnPreDrawCallback": function( oSettings ) {
			$('[data-toggle="tooltip"]').tooltip();
            $('div.dtTopLeft').html('<?php echo $actions_btn; ?>');
		 	$('#loadIMG').show();

		},
		"fnInitComplete": function(oSettings, json) {
     		$('.dt_id_col').removeClass('sorting');
     		$('.dt_id_col').removeClass('sorting_asc');
		 	$('[data-toggle="tooltip"]').tooltip();
     		$('#loadIMG').hide(); 
     		
    	},

	}).fnFilterOnReturn();
	
	// enable / disable / edit links for selected items
	$('#item_table').on( 'draw.dt', function () {
		
		$('a.table-item-edit-link').click(function(event){
			
			var item_id 	= $(this).attr('data-seq');
		
			// update the modal
			$('#edit_task_item').val(item_id);
			
			// update the edit form
			$.ajax({
				type        : 'POST', 				// define the type of HTTP verb we want to use (POST for our form)
				url         : '/ado/process.php', 	// the url where we want to POST
				data		: {update_task_edit_form: item_id},
				dataType    : 'json', 				// what type of data do we expect back from the server
				encode      : true
			})
	
			// using the return data to populate the form
			.done(function(data) {
				$('#edit_item_name').val(data.task_name);
				$('#edit_item_description').val(data.task_desc);
                $('#edit_item_assignment').val(data.assignment);
                $('#edit_item_status').val(data.task_status);
                $('#edit_item_due_date').val(data.due_date);

			});
			
			// open the modal
			$('#edit_modal_item').modal('show');	
			
			// stop the form from submitting the normal way and refreshing the page
			event.preventDefault();
		});
	});
	
	// add form trigger
	$( "#add_item_form_submit" ).click(function(event) {
	
		// show loading div
		$('#loadIMG').show();
	
		// get the data
		var formData = $('#add_item_form').serialize();

		// process the form
		$.ajax({
			type        : 'POST', 				// define the type of HTTP verb we want to use (POST for our form)
			url         : '/ado/process.php', 	// the url where we want to POST
			data        : formData, 			// our data object
			dataType    : 'json', 				// what type of data do we expect back from the server
			encode      : true
		})

		.done(function(data) {
			// create a growl based on the return
			if (data == 1) {
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			} else if (data == 0) {
				$('.item-checkbox').each(function(){ this.checked = false; });
				$('#check_all').show();
				$('#uncheck_all').hide();
				$('#add_item_form')[0].reset();
				<?php echo fn_toastr_success('Success', 'Action completed'); ?> 
			} else if (data == 80) {
				<?php echo fn_toastr_error('Error', 'Item exists'); ?>
			}else{
				// unknown response
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			}
			// redraw datatable
			oTable.fnStandingRedraw();
			
			// hide loading div
			$('#loadIMG').hide();
		});
				
		// close the modal
		$('#add_modal_item').modal('hide');

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	
	// del form trigger
	$( "#del_item_form_submit" ).click(function(event) {
	
		// show loading div
		$('#loadIMG').show();
	
		// get the data
		var formData = $('#del_item_form, #dtForm').serialize();

		// process the form
		$.ajax({
			type        : 'POST', 				// define the type of HTTP verb we want to use (POST for our form)
			url         : '/ado/process.php', 	// the url where we want to POST
			data        : formData, 			// our data object
			dataType    : 'json', 				// what type of data do we expect back from the server
			encode      : true
		})

		.done(function(data) {
			// create a growl based on the return
			if (data == 1) {
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			} else if (data == 0) {
				$('.item-checkbox').each(function(){ this.checked = false; });
				$('#check_all').show();
				$('#uncheck_all').hide();
				<?php echo fn_toastr_success('Success', 'Action completed'); ?>
			} else if (data == 3) {
				<?php echo fn_toastr_error('Error', 'No items selected'); ?>
			}else{
				// unknown response
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			}
			// redraw datatable
			oTable.fnStandingRedraw();
		
			// hide loading div
			$('#loadIMG').hide();		
		});
		
		// close the modal
		$('#del_modal_item').modal('hide');

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});			
	
	// edit form trigger
	$( "#edit_item_form_submit" ).click(function(event) {
	
		// show loading div
		$('#loadIMG').show();
	
		// get the data
		var formData = $('#edit_item_form').serialize();

		// process the form
		$.ajax({
			type        : 'POST', 				// define the type of HTTP verb we want to use (POST for our form)
			url         : '/ado/process.php', 	// the url where we want to POST
			data        : formData, 			// our data object
			dataType    : 'json', 				// what type of data do we expect back from the server
			encode      : true
		})

		.done(function(data) {			
			// create a growl based on the return
			if (data == 1) {
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			} else if (data == 0) {
				$('.item-checkbox').each(function(){ this.checked = false; });
				$('#check_all').show();
				$('#uncheck_all').hide();
				<?php echo fn_toastr_success('Success', 'Action completed'); ?>	 
			} else if (data == 90) {
				<?php echo fn_toastr_error('Error', 'Item exists'); ?>
			}else{
				// unknown response
				<?php echo fn_toastr_error('Error', 'Processing error'); ?>

			}
			// redraw datatable
			oTable.fnStandingRedraw();
		
			// hide loading div
			$('#loadIMG').hide();
		});
				
		// close the modal
		$('#edit_modal_item').modal('hide');
		
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});	

</script> 

</body>

</html>