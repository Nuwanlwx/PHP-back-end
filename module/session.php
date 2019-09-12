<?php
header('Access-Control-Allow-Origin: *'); 
if (session_id() == '') {
    session_start();
}
$session_id = session_id();
require_once dirname(__FILE__).'/utils.php';

$postdata = file_get_contents("php://input");

$_REQUEST = get_request($_REQUEST, $postdata);

// This $action is used on calling module like doctor.php
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : (isset($_SESSION['action']) ? $_SESSION['action'] : ACTION_READ);

$request = $_REQUEST;
unset($request[FIELD_PASS]);
unset($request[FIELD_PASS1]);
unset($request[FIELD_PASS2]);

info(__FILE__, __FUNCTION__, __LINE__, basename($_SERVER['SCRIPT_NAME']), $request, $session_id);
// Do we have an already logged in user in the session?
if (!is_existing_user_session()) {
  // User session data is not there
  $request_session_id = isset($request[FIELD_SESSION_ID]) ? $request[FIELD_SESSION_ID] : "";
  require_once 'db.php';
  if (!is_session_id_valid($request_session_id)) {
    //Session ID submitted in request is not valid
    clear_session();
    
    // Is this a login or user management request
    $script_name = $_SERVER['SCRIPT_NAME'];
    if (!is_login_or_user_management($action, $script_name)) {
      // It is not user login request
      $request_ip = $_SERVER['REMOTE_ADDR'];
      if (is_permitted_ip($request_ip, SMS_PERMITTED_IPS)) {
        // Request is coming from a known IP
        $user = isset($_REQUEST[FIELD_USER]) ? $_REQUEST[FIELD_USER] : "anonymous";
        $pass = isset($_REQUEST[FIELD_PASS]) ? $_REQUEST[FIELD_PASS] : "";
        
        $validation = user_validation($user, $pass);
        if ($validation != OK_USER) {
          // Dialog Digital Reach do not support user/pass
          if (!is_permitted_ip($request_ip, DS_SMS_API_IPS)) {
            // Invalid access credentials
            warn(__FILE__, __FUNCTION__, __LINE__, $validation, $user, $pass, $request_ip, DS_SMS_API_IPS);
            exit(fail_return($validation, false));
          }
        }
      } else {
        // IP is not allowed to access
        warn(__FILE__, __FUNCTION__, __LINE__, ERR_AUTHENTICATION, $request_ip);
        exit(fail_return(ERR_AUTHENTICATION, false));
      }
    }
  }
}

function is_permitted_ip($ip, $allowed_ip_list) {
  return in_array($ip, explode(",", $allowed_ip_list));
}

function clear_session() {
  if (session_id()) {
    if (isset($_SESSION[FIELD_ID])) unset($_SESSION[FIELD_ID]);
    info(__FILE__, __FUNCTION__, __LINE__, session_id(), $_SESSION);
    session_destroy();
  }
}

function is_existing_user_session() {
  return isset($_SESSION[FIELD_USER]) && $_SESSION[FIELD_USER];
}

function is_session_id_valid ($id) {
  return $id && (check_session($id) == OK_USER);
}

function is_login_or_user_management ($action, $script) {
  return $action == ACTION_LOGIN || substr_compare($script, "user.php", -8) == 0;
}
?>
