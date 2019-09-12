<?php
if (session_id() == '') {
    session_start();
}
require_once dirname(__FILE__).'/utils.php';

$postdata = file_get_contents("php://input");
$_REQUEST = get_request($_REQUEST, $postdata);

// This $action is used on calling module like doctor.php
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : (isset($_SESSION['action']) ? $_SESSION['action'] : ACTION_READ);

$request = $_REQUEST;
unset($request[FIELD_PASS]);
unset($request[FIELD_PASS1]);
unset($request[FIELD_PASS2]);

info(__FILE__, __FUNCTION__, __LINE__, basename($_SERVER['SCRIPT_NAME']), $request);
if(!isset($_SESSION[FIELD_USER]) || !$_SESSION[FIELD_USER]) {
 clear_session();
  if($action != ACTION_LOGIN) {
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_AUTHENTICATION, $_REQUEST);
    //exit(fail_return(ERR_AUTHENTICATION, false));
  } 
}

function clear_session() {
  if (session_id()) {
    if (isset($_SESSION[FIELD_ID])) unset($_SESSION[FIELD_ID]);
    info(__FILE__, __FUNCTION__, __LINE__, session_id(), $_SESSION);
    session_destroy();
  }
}
?>
