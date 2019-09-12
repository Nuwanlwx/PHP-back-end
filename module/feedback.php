<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;

switch ($action) { //$action variable defined in session.php
  case ACTION_FIND:
    feedback_read($args);
    break;
  case ACTION_READ:
    feedback_read($args);
    break;
  case ACTION_MOD:
    feedback_update($args);
    break;
  case ACTION_ADD:
    feedback_add($args);
    break;
  case ACTION_DEL:
    feedback_del($args);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}

function feedback_add($args) {
  $field_list = get_field_list(__FILE__);
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }

  require_once 'db.php';
    
  if (!isset($args[FIELD_CUST_ID]) || !$args[FIELD_CUST_ID]) {
    if (isset($_SESSION[FIELD_ID])) $args[FIELD_CUST_ID] = $_SESSION[FIELD_ID];
  }

  if (!isset($args[FIELD_NAME]) || !$args[FIELD_NAME]) {
    if (isset($_SESSION[FIELD_NAME])) $args[FIELD_NAME] = $_SESSION[FIELD_NAME];
  }

  if (!isset($args[FIELD_TEL]) || !$args[FIELD_TEL]) {
    if (isset($_SESSION[FIELD_TEL])) $args[FIELD_TEL] = $_SESSION[FIELD_TEL];
  }

  if (!isset($args[FIELD_TIMESTAMP]) || !$args[FIELD_TIMESTAMP]) {
    $args[FIELD_TIMESTAMP] = date("Y-m-d H:i:s");
  }
  
  return db_insert(TBL_FEEDBACK, $field_list, $args);
}

function feedback_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_FEEDBACK, $field_list, $arg, false);
        $succ_count++;
      } 
    }
    if ($succ_count) {
      return succ_return(OK_DATA_DELETE, true);
    } else {
      $error = ERR_PARA_NOT_DEFINED;
      warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
      exit(fail_return($error, false));
    }
  } else {
    return db_delete(TBL_FEEDBACK, $field_list, $args);
  }
}

function feedback_read() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $args[FIELD_ORDER_BY] = FIELD_ID;
  info(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  require_once 'db.php';
  $feedback_list = db_read(TBL_FEEDBACK, $args, false, false);
  return succ_return($feedback_list, true, true, count($feedback_list));
}

function feedback_update() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  
  return db_update(TBL_FEEDBACK, array(FIELD_ID), $args);
}
?>