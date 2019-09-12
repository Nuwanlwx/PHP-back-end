<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';

// debug(__FILE__, __FUNCTION__, __LINE__, $action, $request);

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;

if (!isset($args[FIELD_FOLLOWUP_TS]) || !$args[FIELD_FOLLOWUP_TS]) {
  if (!isset($args[FIELD_DATE]) || !$args[FIELD_DATE]) {
    $args[FIELD_FOLLOWUP_TS] = date("Y-m-d H:i:s");
  } else {
    $args[FIELD_FOLLOWUP_TS] = $args[FIELD_DATE];
  }
}

switch ($action) { //$action variable defined in session.php
  case ACTION_FIND:
    sale_followup_read($args);
    break;
  case ACTION_READ:
    sale_followup_read($args);
    break;
  case ACTION_MOD:
    sale_followup_update($args);
    break;
  case ACTION_ADD:
    sale_followup_add($args);
    break;
  case ACTION_DEL:
    sale_followup_del($args);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}

function sale_followup_add($args) {
  $resp = fail_return(ERR_DB_INSERT, false);   
  $field_list = get_field_list(__FILE__);
  
  require_once 'db.php';
    
  if (!isset($args[FIELD_SALE_ID]) || !$args[FIELD_SALE_ID]) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, FIELD_SALE_ID, $args[FIELD_SALE_ID]);
    exit(fail_return($error, false));
  }
    
  $sale_followup_field_list = get_field_list(TBL_SALE_FOLLOWUP);
    
  unset($args[FIELD_ACTION]);
  unset($args[FIELD_SESSION_ID]);
  foreach ($args as $sale_followup_para => $sale_followup_val) {
    if (!in_array($sale_followup_para, array(FIELD_SALE_ID, FIELD_FOLLOWUP_TS, FIELD_DATE))) {
        $sale_followup_info_val_set = array (
            FIELD_SALE_ID => $args[FIELD_SALE_ID],    
            FIELD_PARA => $sale_followup_para, 
            FIELD_VAL => $sale_followup_val, 
            FIELD_FOLLOWUP_TS => $args[FIELD_FOLLOWUP_TS]
        );
        $resp = db_insert(TBL_SALE_FOLLOWUP, $sale_followup_field_list, $sale_followup_info_val_set, false, false);
    }
  }
  return succ_return($resp, true, true);
}

function sale_followup_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  unset($args[FIELD_FOLLOWUP_TS]);
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_SALE_FOLLOWUP, $field_list, $arg, false);
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
    return db_delete(TBL_SALE_FOLLOWUP, $field_list, $args);
  }
}

function sale_followup_read() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $args[FIELD_ORDER_BY] = "id";
  unset($args[FIELD_FOLLOWUP_TS]);
  info(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  require_once 'db.php';
  $sale_followup_list = db_read(TBL_SALE_FOLLOWUP, $args, false, false);
  return succ_return($sale_followup_list, true, true, count($sale_followup_list));
}

function sale_followup_update() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  debug(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  return db_update(TBL_SALE_FOLLOWUP, array(FIELD_ID), $args);
}
?>