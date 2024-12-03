<?php
$session_no_chk = 1;

require_once($_SERVER["DOCUMENT_ROOT"].'/ado/core.php');

# checks to make sure this is an ajax call
if(empty($_SERVER['HTTP_X_REQUESTED_WITH'])){ syslog(LOG_INFO|LOG_MAIL, "ajax submission error"); exit(); }

# remote ip
$g_ip = trim($_SERVER['REMOTE_ADDR']);

# login request - login.php
if(isset($_POST['index_login_page_submit'])){

	# submit error check
	if($_POST['index_login_page_submit'] != 1){
		syslog(LOG_INFO|LOG_MAIL, "index login request general submission error");
		$return	= 1; echo json_encode($return); exit();
	}

	if(empty($_POST['login_name'])){
		sleep(1); $return = 78; echo json_encode($return); exit();
	}else{
		$login = trim(strtolower($_POST['login_name']));
	}

	if(empty($_POST['login_passwd'])){
		sleep(1); $return = 79; echo json_encode($return); exit();
	}else{
		$passwd = trim($_POST['login_passwd']);
	}

	if($login && $passwd){
		# pull the user's password from the DB for comparison
		$aa = array('email'=>$login, 'username'=>$login);
		$a = "SELECT password FROM users_admin WHERE (LOWER(email)=:email OR LOWER(username)=:username) LIMIT 1";
		$b = fn_pdo($a, $aa); $c = $b->fetch();

		# explode the password to get the salt
		if(!empty($c[0])){
			$result_db_passwd = $c[0];
			list($db_password, $db_salt) = explode(":", $result_db_passwd);
		}else{
			$result_db_passwd = FALSE; $db_password = FALSE; $db_salt = FALSE;
		}
	
		# now combine the submitted password with the salt, apply md5 and compare to the db value
		if($result_db_passwd && ($db_password == md5($passwd.$db_salt))){
			# user passed authentication
			$aa = array('email'=>$login, 'username'=>$login, 'password'=>$result_db_passwd);
			$a = "SELECT id, enabled FROM users_admin WHERE (LOWER(email)=:email OR LOWER(username)=:username) AND password=:password";
			$b = fn_pdo($a, $aa);
			$c = $b->fetch();
			
			$user_uid   = intval($c[0]);
			$enabled 	= intval($c[1]);

			if($enabled == 0){
				$user_uid = FALSE;
				syslog(LOG_INFO|LOG_MAIL, "attempt to login to disabled account: ".$login);
				sleep(1); $return = 80; echo json_encode($return); exit();

			}else{
				# regular session login
				$_SESSION['x_user']['uid'] = $user_uid;
				
				$aa = array('id'=>$user_uid);
				$a = "SELECT username, fname, lname, email FROM users_admin WHERE id=:id LIMIT 1";
				$b = fn_pdo($a, $aa);
				$c = $b->fetch();

				$_SESSION['x_user']['user_name'] 	= $c[0];
				$_SESSION['x_user']['first_name'] 	= $c[1];
				$_SESSION['x_user']['last_name'] 	= $c[2];				
                $_SESSION['x_user']['email'] 	    = $c[3];

				# log user login
				syslog(LOG_INFO|LOG_MAIL, "user login - uid: ".$user_uid);
				$return = 0; echo json_encode($return); exit();
			}

		}else{

			# Login failure
			syslog(LOG_INFO|LOG_MAIL, "user login failure: ".$login);
			sleep(1); $return = 81; echo json_encode($return); exit();
		}
	}
}

# task list request - datatables
if(isset($_GET['task_list_request'])){
	
	if($_GET['task_list_request'] != 1){
		syslog(LOG_INFO|LOG_MAIL, "1955: task object list request general submission error");
		$return	= 1; echo json_encode($return); exit();
	}

	$aa = array(); # filtering array
	$aColumns = array('id', 'task_name', 'assignment', 'task_status', 'task_date', 'due_date', 'checkbox');
	$iColumnCount = count($aColumns);

	# Indexed column 
	$sIndexColumn = 'id';
	$sTable = 'tasks';
	$log_prefix = 'tasks';
	$log_order = 'ORDER BY id';

	# Paging
	$sLimit = "";
	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1' ){
		$sLimit = "LIMIT ".intval($_GET['iDisplayStart']).", ".intval($_GET['iDisplayLength']);
	}
	
	# Ordering
	$sOrder = $log_order;
	if(isset( $_GET['iSortCol_0'])){
		$sOrder = "ORDER BY  ";
		for($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
			if($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true"){
				$sortDir = (strcasecmp($_GET['sSortDir_'.$i], 'ASC') == 0) ? 'DESC' : 'ASC';
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ". $sortDir .", ";
			}
		}
		
		$sOrder = substr_replace($sOrder, "", -2);
		if($sOrder == "ORDER BY"){ $sOrder = $log_order; }
	}
	
	# Filtering
	$sWhere = "";
	if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
		$sWhere = "WHERE (";
		for($i=0 ; $i<count($aColumns) ; $i++){
			if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true"){
				$sWhere .= $aColumns[$i]." LIKE :search_".$i." OR ";
				# Bind parameters
				$aa['search_'.$i] = '%'.$_GET['sSearch'].'%';
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}

	# Individual column filtering
	for($i=0 ; $i<count($aColumns) ; $i++){
		if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != ''){
			if($sWhere == "" ){ $sWhere = "WHERE "; }else{ $sWhere .= " AND "; }
			$sWhere .= $aColumns[$i]." LIKE :search".$i." ";
		}
	}

	# total number of records
	$a = "SELECT COUNT(".$sIndexColumn.") FROM ".$sTable;
	$b = fn_pdo($a); $c = $b->fetch(); $iTotal = intval($c[0]);
	
	# Data set length after filtering
	if($_GET['sSearch'] != ""){
		$a = 'SELECT COUNT('.$sIndexColumn.') FROM '.$sTable.' '.$sWhere;
		$b = fn_pdo($a, $aa); $c = $b->fetch(); $iFilteredTotal = intval($c[0]);
	}else{ $iFilteredTotal = $iTotal; }
	
	# main query 
	$a = 'SELECT '.implode(", ", $aColumns).' FROM '.$sTable.' '.$sWhere.' '.$sOrder.' '.$sLimit;
	$b = fn_pdo($a, $aa);

	# output
	$output = array(
		"sEcho"                => intval($_GET['sEcho']),
		"iTotalRecords"        => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData"               => array(),
	);

	while($aRow = $b->fetch()){
				
		$row = array();	
		$row[] = fn_datatables_create_checkbox($aRow['id']);
		$row[] = $aRow['task_name'];
		$row[] = $aRow['assignment'];
		$row[] = $aRow['task_status'];
		$row[] = $aRow['task_date'];
		$row[] = $aRow['due_date'];
		$row[] = fn_table_item_create_edit_link($aRow['id']);
		$output['aaData'][] = $row;
	}

	echo json_encode($output); exit();
}

# task list - DELETE 
if(isset($_POST['del_task_item'])){
	
	# submit error check
	if($_POST['del_task_item'] != 1){
		syslog(LOG_INFO|LOG_MAIL, "2387: delete submission error");
		$return	= 1; echo json_encode($return); exit();
	}
	
	if(!isset($_POST['dt_checkbox_select_item_id'])){
		syslog(LOG_INFO|LOG_MAIL, "2388: delete empty delete request");
		$return	= 3; echo json_encode($return); exit();
	}
	
	# delete the selected items
	foreach($_POST['dt_checkbox_select_item_id'] as $id){

		# delete from assets_file_mimes
		$aa = array('id'=>$id);
		$a = "DELETE FROM tasks WHERE id=:id";
		fn_pdo($a, $aa);
	}

	$return = 0; echo json_encode($return); exit();
}

# task - ADD 
if(isset($_POST['add_task_item'])){
	# submit error check
	if($_POST['add_task_item'] != 1){
		syslog(LOG_INFO|LOG_MAIL, "3027: add task general submission error");
		$return	= 1; echo json_encode($return); exit();
	}
	
	# get the information being added
	$name 			= trim($_POST['add_item_name']); 
	$desc 			= trim($_POST['add_item_description']);	
	$assignment 	= trim($_POST['add_item_assignment']);
	$status 		= trim($_POST['add_item_status']);
	$due_date		= trim($_POST['add_item_due_date']);
		
	# add the task
	$aa = array('task_name'=>$name, 'task_desc'=>$desc, 'assignment'=>$assignment, 'task_status'=>$status, 'due_date'=>$due_date);

	$a= "INSERT INTO tasks (task_name, task_desc, assignment, task_status, due_date) 
			VALUES (:task_name, :task_desc, :assignment, :task_status, :due_date)";
	fn_pdo($a, $aa);
	
	# event log
	syslog(LOG_INFO|LOG_MAIL, "3000: task added");
	
	$return = 0; echo json_encode($return); exit();				
}

# tasks - POPULATE edit form 
if(isset($_POST['update_task_edit_form'])){
	$id = intval($_POST['update_task_edit_form']);
	
	$return = array();

	# get the admin info
	$aa = array('id'=>$id);
	$a = "SELECT task_name, task_desc, assignment, task_status, due_date FROM tasks WHERE id=:id LIMIT 1";
	$b = fn_pdo($a, $aa);	$c = $b->fetch();	
	$return['task_name'] 		= $c[0];
	$return['task_desc'] 		= $c[1];
	$return['assignment'] 		= $c[2];
	$return['task_status'] 		= $c[3];
	$return['due_date'] 		= $c[4];

	echo json_encode($return); exit();
}

# tasks - EDIT 
if(isset($_POST['edit_task_item'])){
	# submit error check
	if(!is_numeric($_POST['edit_task_item'])){
		syslog(LOG_INFO|LOG_MAIL, "3035: edit general submission error");
		$return	= 1; echo json_encode($return); exit();
	}

	# get the information being edited
	$id 			= trim($_POST['edit_task_item']);	
	$task_name		= trim($_POST['edit_item_name']);	
	$task_desc 		= trim($_POST['edit_item_description']);	
	$assignment 	= trim($_POST['edit_item_assignment']);
	$task_status	= trim($_POST['edit_item_status']);
	$due_date 		= trim($_POST['edit_item_due_date']);
	
	# update 
	$aa = array('id'=>$id, 'task_name'=>$task_name, 'task_desc'=>$task_desc, 'assignment'=>$assignment, 'task_status'=>$task_status, 'due_date'=>$due_date);
	$a = "UPDATE tasks SET task_name=:task_name, task_desc=:task_desc, assignment=:assignment, task_status=:task_status, due_date=:due_date WHERE id=:id LIMIT 1";
	fn_pdo($a, $aa);

	# event log
	syslog(LOG_INFO|LOG_MAIL, "3038: task updated / id: $id");	
	$return = 0; echo json_encode($return); exit();				
}

# index - register request 
if(isset($_POST['index_register_page_submit'])){
	
	$data = array();
	$data['fname'] 		= trim($_POST['FirstName']);
	$data['lname'] 		= trim($_POST['LastName']);
	$data['email'] 		= trim($_POST['InputEmail']);
	$data['password1']  = trim($_POST['InputPassword']);
	$data['password2']  = trim($_POST['RepeatPassword']);
	$data['invite_code']   = trim($_POST['inviteCode']);

	# email check
	if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
		/* invalid email */
		sleep(1);
		syslog(LOG_INFO|LOG_MAIL, "3165: index register fail - invalid email format: ".$data['email']);
		$return	= 83; echo json_encode($return); exit();
	}

	# availability check
	$aa = array('email'=>$data['email']);	
	$a  = "SELECT count(id) FROM users_admin WHERE email=:email";
	$b  = fn_pdo($a, $aa); $c = $b->fetch();
	
	if($c[0] != 0){
		# email is taken
		sleep(2);
		syslog(LOG_INFO|LOG_MAIL, "3164: index register fail - email is already in use: ".$data['email']);
		$return	= 85; echo json_encode($return); exit();
	}

	# check names
	if(empty($data['fname']) || empty($data['lname'])){
		sleep(2);
		syslog(LOG_INFO|LOG_MAIL, "6164: index register fail - last or first name empty");
		$return	= 55; echo json_encode($return); exit();
	}

	# password check
	if(empty($data['password1']) || empty($data['password2'])){
		sleep(2);
		syslog(LOG_INFO|LOG_MAIL, "4164: index register fail - one or more passwords empty");
		$return	= 45; echo json_encode($return); exit();
	}

	if($data['password1'] != $data['password2']){
		# passwords do not match
		sleep(2);
		syslog(LOG_INFO|LOG_MAIL, "8164: index register fail - password mismatch");
		$return	= 75; echo json_encode($return); exit();
	}	

	# setup the new password
	$salt = @md5(@uniqid(@microtime(), 1)).@getmypid();
	$salt = trim(substr($salt,0,32));
	$password = md5($data['password1'].$salt);
	$password = $password.':'.$salt;
	$data['password1'] = $password;

	# invite code check
	if($server_cf['INVITE_CODE'] != $data['invite_code']){
		# code fail
		sleep(2);
		syslog(LOG_INFO|LOG_MAIL, "7164: index register fail - invalid invite code");
		$return	= 65; echo json_encode($return); exit();
	}

	# insert 
	$aa = array('lname'=>$data['lname'], 'fname'=>$data['fname'], 'username'=>$data['email'], 'email'=>$data['email'], 'password'=>$data['password1']);
	$a = "INSERT INTO users_admin (lname, fname, username, email, password) VALUES (:lname, :fname, :username, :email, :password)";
	fn_pdo($a, $aa);

	$return = 0; echo json_encode($return); exit();
}