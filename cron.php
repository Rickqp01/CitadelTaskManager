<?php
$session_no_chk = 1;

#$to_email   = 'riki.k.benton@gmail.com';
#$to_email   = 'mailborder@gmail.com';
#$to_email   = 'jerry.benton@me.com';
#$to_email = 'rbenton2@nation.citadel.edu';
$to_email = 'jerry.benton@mailborder.com';

$from_email = 'tasks@calbonic.com';

require_once($_SERVER["DOCUMENT_ROOT"].'/ado/core.php');

if(!isset($_GET['key'])){
    echo 'go away'; exit();
}

$key = strtolower(trim($_GET['key']));

if($key != 'supersecretkey'){
    echo 'wrong key dummy'; exit();
}

$a = "SELECT task_name, assignment, task_status, due_date FROM tasks WHERE task_status!='complete'";
$b = fn_pdo($a);

$tasks = NULL;

while($c = $b->fetch()){
    $tasks .= 'Name: '.$c[0].PHP_EOL;
    $tasks .= 'Assignment: '.$c[1].PHP_EOL;
    $tasks .= 'Status: '.$c[2].PHP_EOL;
    $tasks .= 'Due Date: '.$c[3].PHP_EOL;
    $tasks .= ''.PHP_EOL;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/ado/class.mail.php');

/* mail the report */
$to = $to_email;
$from = $from_email;
$subject = 'Calbonic Task Report';
$body  = 'The following tasks are pending in a state other than "complete".'."\n";
$body .= ''."\n";
$body .= $tasks;

$send = SimpleMail::make()
        ->setTo($to)
        ->setFrom($from, 'Calbonic Task Server')
        ->setReplyTo($from)
        ->setSubject($subject)
        ->setMessage($body)
        ->setParameters('-f '.$from)
        ->send();

syslog(LOG_INFO|LOG_MAIL, "3792: cron report sent to: ".$to_email);




