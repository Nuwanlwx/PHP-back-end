<?php
require_once dirname(__FILE__).'/session.php';

debug(__FILE__, __FUNCTION__, __LINE__, $action, $request);

switch ($action) { //$action variable defined in session.php
  case ACTION_ADD:
    doctor_add($request);
    break;
  case ACTION_DEL:
    doctor_del($request);
    break;
  case ACTION_MOD:
    doctor_update($request);
    break;
  case ACTION_READ:
    doctor_read($request);
    break;
  case ACTION_FIND:
    doctor_read($request);
    break;
  case ACTION_SPECIALITY:
    doctor_speciality();
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $request);
}

function doctor_add() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }

  require_once 'db.php';
  $args[FIELD_VAT] = (isset($args[FIELD_VAT]) && $args[FIELD_VAT]) ? $args[FIELD_VAT] : ($args[FIELD_CHARGE] + $args[FIELD_INST_CHARGE]) * 15/100;
  $total = $args[FIELD_CHARGE] + $args[FIELD_INST_CHARGE] + $args[FIELD_VAT];
  $args[FIELD_ROUND_OFF] = (isset($args[FIELD_ROUND_OFF]) && $args[FIELD_ROUND_OFF]) ? $args[FIELD_ROUND_OFF] : round($total, -1) - $total;
  return db_insert(TBL_DOCTOR, $field_list, $args);
}

function doctor_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_DOCTOR, $field_list, $arg, false);
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
    return db_delete(TBL_DOCTOR, $field_list, $args);
  }
}

function doctor_read() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $args[FIELD_ORDER_BY] = "name";
  warn(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  require_once 'db.php';
  return db_read(TBL_DOCTOR, $args);
}

function doctor_update() {
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
  return db_update(TBL_DOCTOR, array(FIELD_ID), $args);
}

function doctor_speciality() {
  require_once 'db.php';
  $speciality_list = json_decode(db_search(TBL_DOCTOR, array(FIELD_SPECIALITY), array(), false), true);
  $details = array_values(array_unique($speciality_list[JSON_DETAILS], SORT_REGULAR));
  sort($details);
  return succ_return($details, true, true, count($details));
}
?>