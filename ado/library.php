<?php

# create toastr error message
function fn_toastr_error($label, $message){

	$return = 'toastr.error("'.$message.'","'.$label.'",{
			"closeButton": true,
			"debug": false,
			"newestOnTop": true,
			"progressBar": true,
			"positionClass": "toast-top-full-width",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "8000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		});';

	return $return;
}

# create toastr success message 
function fn_toastr_success($label, $message){

	$return = 'toastr.success("'.$message.'","'.$label.'",{
			"closeButton": true,
			"debug": false,
			"newestOnTop": true,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		});';

	return $return;

}

# mysql PDO database function
function fn_pdo($a, $aa=array()){
	global $mysql_pdo;

	if($mysql_pdo == false){ return false; }

	try {
		$r = $mysql_pdo->prepare($a);
		$r->execute($aa); 

		if($r->errorCode() != 0){
		    syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".json_encode($r->errorInfo()));
		    syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		    syslog(LOG_INFO|LOG_MAIL, "PDO query: ".$a);
		}
		return $r; 
	} catch (Throwable $t) {
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$t->getMessage());
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		syslog(LOG_INFO|LOG_MAIL, "PDO query: ".$a);
		return NULL;
	}catch (PDOException $e){
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$e->getMessage());
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		syslog(LOG_INFO|LOG_MAIL, "PDO query: ".$a);
		return NULL;	
	}
}

# PDO function that requires PDO object
function fn_x_pdo($pdo, $a, $aa=array()){
	try {
		$r = $pdo->prepare($a);
		$r->execute($aa); 

		if($r->errorCode() != 0){
		    syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".json_encode($r->errorInfo()));
		    syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		    syslog(LOG_INFO|LOG_MAIL, "PDO query: ".$a);
		}
		return $r; 
	} catch (Throwable $t) {
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$t->getMessage());
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		return NULL;
	}catch (PDOException $e){
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$e->getMessage());
		syslog(LOG_INFO|LOG_MAIL, "PDO ERROR: ".$_SERVER['SCRIPT_NAME']);
		return NULL;	
	}
}

# mysql DB connection
function fn_load_mysql_pdo(){
	global $server_cf;

	$dsn = 'mysql:host='.$server_cf['DB_HOST'].';dbname='.$server_cf['DB_NAME'].';port='.$server_cf['DB_PORT'].';charset=utf8mb4';
	$opt = array(
		'PDO::ATTR_ERRMODE'            => 'PDO::ERRMODE_WARNING',
		'PDO::ATTR_DEFAULT_FETCH_MODE' => 'PDO::FETCH_BOTH',
		'PDO::ATTR_AUTOCOMMIT'		   => TRUE,
		'PDO::ATTR_EMULATE_PREPARES'   => FALSE,
	);
	
	try {
		$mysql_pdo = new PDO($dsn, $server_cf['DB_USER'], $server_cf['DB_PASS'], $opt);
	}catch (PDOException $e){
		# db connection failed
		return false;
		syslog(LOG_INFO|LOG_MAIL, "1681: PDO connection error: ".$e->getMessage());
	}

	return $mysql_pdo;
}

# creates a checkbox for datatables
function fn_datatables_create_checkbox($id){ 
	$i = '<div class="text-center no-padding"><input type="checkbox" name="dt_checkbox_select_item_id[]" value="'.$id.'" class="item-checkbox" style="cursor:pointer"></div>'; 
	return $i; 
}

# creates the edit link for a table item
function fn_table_item_create_edit_link($id){	 
		
	$item = '<a href="#" data-seq="'.$id.'" class="no-spinner table-item-edit-link fa-make-blue" data-toggle="tooltip" title="Edit"><i class="fas fa-2x fa-edit"></i></a>';
	return $item;
}