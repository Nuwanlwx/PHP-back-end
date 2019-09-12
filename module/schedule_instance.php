<?php
require_once dirname(__FILE__).'/session.php';

switch ($action) { //$action variable defined in session.php
  case ACTION_ADD:
    schedule_instance_add($_REQUEST);
    break;
  case ACTION_DEL:
    schedule_instance_del($_REQUEST);
    break;
  case ACTION_MOD:
    schedule_instance_mod($_REQUEST);
    break;
  case ACTION_READ:
  case ACTION_FIND:
    schedule_instance_search($_REQUEST);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}

function schedule_instance_add() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  return db_insert(TBL_SCHEDULE_INSTANCE, $field_list, $args);
}

function schedule_instance_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';

  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_SCHEDULE_INSTANCE, $field_list, $arg, false);
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
    return db_delete(TBL_SCHEDULE_INSTANCE, $field_list, $args);
  }
}

function schedule_instance_search() {
  //TODO: should return appointment count too
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  $tbl = TBL_SCHEDULE_INSTANCE;
  $args[FIELD_ORDER_BY] = "`".FIELD_DATE."`, `".FIELD_TIME."`";
  return db_read($tbl, $args);
}

function schedule_instance_mod() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  return db_update(TBL_SCHEDULE_INSTANCE, array(FIELD_ID), $args);
}
?>