<?php
require_once dirname(__FILE__).'/session.php';

switch ($action) { //$action variable defined in session.php
  case ACTION_ADD:
    appointment_add($_REQUEST);
    break;
  case ACTION_MOD:
  case ACTION_REFUND:
    appointment_mod($_REQUEST);
    break;
  case ACTION_DEL:
    appointment_del($_REQUEST);
    break;
  case ACTION_READ:
    appointment_search($_REQUEST);
    break;
  case ACTION_FIND:
    appointment_search($_REQUEST);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}

function appointment_add() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!isset($args[FIELD_PAYTIME])) $args[FIELD_PAYTIME] = '';
  if (!isset($args[FIELD_REFUND])) $args[FIELD_REFUND] = 0;
  if (!isset($args[FIELD_INST_REFUND])) $args[FIELD_INST_REFUND] = 0;
  if (!isset($args[FIELD_REFUNDTIME])) $args[FIELD_REFUNDTIME] = '';
  require_once 'db.php';
  $args[FIELD_REF] = get_appointment_ref($args[FIELD_STATUS]);
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  $args = set_appointment_no($args);
  $out = db_insert(TBL_APPOINTMENT, $field_list, $args, false, false);
  if ($out == OK_DATA_INSERT) {
    return json_return(array(JSON_SUCCESS => true, JSON_DETAILS => OK_DATA_INSERT, JSON_STATUS => true, 
      FIELD_APPOINTMENT => $args[FIELD_APPOINTMENT], FIELD_ID => mysql_insert_id()), true);
  } else {
    warn(__FILE__, __FUNCTION__, __LINE__, $out);
    return fail_return($out);
  }
}

function appointment_del() {
  $ret = OK_DATA_DELETE;
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $field_list = array(FIELD_ID);
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_APPOINTMENT, $field_list, $arg, false);
        $succ_count++;
      }
    }
    if (!$succ_count) {
      $error = ERR_PARA_NOT_DEFINED;
      warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
      exit(fail_return($error, false));
    }
  } else {
    $ret = db_delete(TBL_APPOINTMENT, $field_list, $args, false, false);
  }
  update_schedule_instance_patient_count($args[FIELD_SCHEDULE]);
  return succ_return($ret, true);
}

function appointment_search() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  if(isset($args[FIELD_ID]) && $args[FIELD_ID]) {
    unset($args[FIELD_DOCTOR]);
    unset($args[FIELD_NIC]);
    unset($args[FIELD_PATIENT]);
    unset($args[FIELD_DATE]);
    unset($args[FIELD_REF]);
    unset($args[FIELD_SCHEDULE]);
    unset($args[FIELD_STATUS]);
    unset($args[FIELD_TEL]);
  }
  if(isset($args[FIELD_DOCTOR]) && !is_numeric($args[FIELD_DOCTOR])) {
    $args[TBL_DOCTOR.".".FIELD_NAME] = $args[FIELD_DOCTOR];
    unset($args[FIELD_DOCTOR]);
  }
  $args[FIELD_ORDER_BY] = "ref";
  return db_read(TBL_APPOINTMENT, $args);
}

function set_appointment_no($para) {
  $db_appointment_list = db_search(TBL_APPOINTMENT, array(FIELD_APPOINTMENT), array(FIELD_SCHEDULE => $para[FIELD_SCHEDULE]), false, false);
  
  function _getAppointment($details) { 
    return $details[FIELD_APPOINTMENT]; 
  } 
  
  $appointment_list = array();
  $appointment_list = array_map('_getAppointment', $db_appointment_list);
  $schedule_info = db_search(TBL_SCHEDULE_INSTANCE, array(FIELD_MAX), array(FIELD_ID => $para[FIELD_SCHEDULE]), false, false);
  $allowed_patient_count = $schedule_info[0][FIELD_MAX];
  $current_patient_count = count($appointment_list);
  if($current_patient_count < $allowed_patient_count) {
	  $para[FIELD_APPOINTMENT] = ($appointment_list) ? max($appointment_list)+1 : 1;
    db_update(TBL_SCHEDULE_INSTANCE, array(FIELD_ID, FIELD_PATIENT), array(FIELD_ID=>$para[FIELD_SCHEDULE], FIELD_PATIENT=>$current_patient_count+1), false, false);
	  return $para;
	} else {
	  db_update(TBL_SCHEDULE_INSTANCE, array(FIELD_ID, FIELD_PATIENT), array(FIELD_ID=>$para[FIELD_SCHEDULE], FIELD_PATIENT=>$current_patient_count), false, false);
	  warn(__FILE__, __FUNCTION__, __LINE__, ERR_MAX_APPOINTMENT, $current_patient_count, $allowed_patient_count);
	  exit(fail_return(ERR_MAX_APPOINTMENT, false));
	}
}

function appointment_mod() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  $args[FIELD_REF] = get_appointment_ref($args[FIELD_STATUS], $args[FIELD_ID]);
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  return db_update(TBL_APPOINTMENT, array(FIELD_ID), $args);
}
?>