<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';

switch ($action) {
  case ACTION_LOGIN:
    login($_REQUEST);
    break;
  case ACTION_LOGOUT:
    logout();
    break;
  case ACTION_PASSWD:
    passwd($_REQUEST);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_SESSION);
}

function logout() {
  info(__FILE__, __FUNCTION__, __LINE__, OK_LOGOUT, $_SESSION);
  clear_session();
  succ_return(OK_LOGOUT);
}

function login() {
  clear_session();
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!((isset($args[FIELD_USER]) && isset($args[FIELD_PASS])))) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args);
    exit(fail_return($error, false, true, false));
  }
  $user = $args[FIELD_USER];
  $pass = $args[FIELD_PASS];
  require_once 'db.php';
  user_login($user, $pass);
}

function passwd() {
  require_once 'db.php';
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if($args[FIELD_ID] == $_SESSION[FIELD_ID]) {
    if (!isset($args[FIELD_PASS]) || !$args[FIELD_PASS]) {
      $error = ERR_CURRENT_PASS_NA;
      warn(__FILE__, __FUNCTION__, __LINE__, $error);
      exit(fail_return($error, false));
    } else {
      $user = ($args[FIELD_USER]) ? $args[FIELD_USER] : $_SESSION[FIELD_USER];
      $validation = user_validation($user, $args[FIELD_PASS]);
      if($validation != OK_USER) {
        warn(__FILE__, __FUNCTION__, __LINE__, $validation);
        exit(fail_return($validation, false));
      }
    }
  } else {
    if ($_SESSION[FIELD_TYPE] != USER_TYPE_ADMIN) {
      $error = ERR_PERMISSION_DENIED;
      warn(__FILE__, __FUNCTION__, __LINE__, $error);
      exit(fail_return($error, false));
    }
  }
  // Check new pass
  if (!isset($args[FIELD_PASS1]) || !$args[FIELD_PASS1]) {
    $error = ERR_NEW_PASS_NA;
    warn(__FILE__, __FUNCTION__, __LINE__, $error);
    exit(fail_return($error, false));
  }  
  // If both pass1 & pass2 are submitted, they should be equal  
  if (isset($args[FIELD_PASS2]) && $args[FIELD_PASS1] != $args[FIELD_PASS2]) {
    $error = ERR_NEW_PASS_NOT_MATCH;
    warn(__FILE__, __FUNCTION__, __LINE__, $error);
    exit(fail_return($error, false));
  }
  // Update user details
  $args[FIELD_PASS] = pass_encrypt($args[FIELD_PASS1]);
  return db_update(TBL_USER, array(FIELD_ID), $args);
}
?>
