<?php
require_once dirname(__FILE__).'/session.php';

switch ($action) { //$action variable defined in session.php
  case ACTION_ADD:
    schedule_add($_REQUEST);
    break;
  case ACTION_DEL:
    schedule_del($_REQUEST);
    break;
  case ACTION_MOD:
    schedule_update($_REQUEST);
    break;
  case ACTION_READ:
    schedule_read($_REQUEST);
    break;
  case ACTION_FIND:
    schedule_search($_REQUEST);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}

function schedule_add() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  $out = db_insert(TBL_SCHEDULE, $field_list, $args, false, false);
  if ($out == OK_DATA_INSERT) {
    $args[FIELD_ID] = mysql_insert_id();
    return schedule_populate($args);
  } else {
    exit(fail_return($out, false));
  }
}

function schedule_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  $out = db_delete(TBL_SCHEDULE, $field_list, $args, false, false);
  if ($out == OK_DATA_DELETE) {
    return schedule_depopulate($args);
  } else {
    exit(fail_return($out, false));
  }
}

function schedule_read() {
  //TODO: should return appointment count too
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  return db_read(TBL_SCHEDULE, $args);
}

function schedule_search() {
  //TODO: should return appointment count too
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  return db_search(TBL_SCHEDULE, $field_list, $args);
}

function schedule_update() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  $old_list = db_search(TBL_SCHEDULE, $field_list, array(FIELD_ID => $args[FIELD_ID]), false, false);

  if (count($old_list) == 1) {
    $out = db_update(TBL_SCHEDULE, array(FIELD_ID), $args, false, false);
    if ($out == OK_DATA_UPDATE) {
      return schedule_repopulate($args, $old_list[0]);
    } else {
      exit(fail_return($out, false));
    }
  } else {
    $error = ERR_DB_INCORRECT_ROW_COUNT;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $old_list, $args);
    exit(fail_return($error, false));
  }
}

function schedule_repopulate($new, $old) {
  if (array_key_exists(FIELD_ID, $new)) {
    $old_day_list = get_schedule_day_list($old);
    $old_date_list = array();
    foreach ($old_day_list as $day) {
      $date_list = get_future_date_list_between_two_days($old[FIELD_START_DATE], $old[FIELD_END_DATE], $day);
      $old_date_list = array_merge($old_date_list, $date_list);
    }
    $new_day_list = get_schedule_day_list($new);
    $new_date_list = array();
    foreach ($new_day_list as $day) {
      $new_date_list = array_merge($new_date_list, get_future_date_list_between_two_days($new[FIELD_START_DATE], $new[FIELD_END_DATE], $day));
    }
    $added_date_list = array_diff($new_date_list, $old_date_list);
    $val_list_of_list = array();
    foreach ($added_date_list as $date) {
      array_push($val_list_of_list, 
        array(FIELD_SUMMARY => $new[FIELD_ID], FIELD_DOCTOR => $new[FIELD_DOCTOR], FIELD_DATE => $date, 
          FIELD_TIME => $new[FIELD_TIME], FIELD_MAX => $new[FIELD_MAX], FIELD_STATUS => 0, FIELD_PATIENT => 0));
    }
    $out = db_multi_insert(TBL_SCHEDULE_INSTANCE, get_field_list("schedule_instance"), $val_list_of_list, false, false);
    if ($out != OK_DATA_INSERT) {
      warn(__FILE__, __FUNCTION__, __LINE__, $out);
      exit(fail_return($out, false));
    }
    
    $removed_date_list = array_diff($old_date_list, $new_date_list);
    foreach ($removed_date_list as $date) {
      $out = db_delete(TBL_SCHEDULE_INSTANCE, array(FIELD_SUMMARY, FIELD_DATE), array(FIELD_SUMMARY => $new[FIELD_ID], FIELD_DATE => $date), false, false);
      if ($out != OK_DATA_DELETE) {
        warn(__FILE__, __FUNCTION__, __LINE__, $out);
        exit(fail_return($out, false));
      }
    }
    
    $update_date_list = array_intersect($old_date_list, $new_date_list);
    $val_list_of_list = array();
    $new_instance = $new;
    $new_instance[FIELD_SUMMARY] = $new_instance[FIELD_ID];
    unset($new_instance[FIELD_ID]);
    $old_instance = $old;
    unset($old_instance[FIELD_ID]);
    $old_instance[FIELD_SUMMARY] = $old_instance[FIELD_ID];
    
    foreach ($update_date_list as $date) {
      //TODO: This update seems updateing all entries of the instance table. Need to check.
      $out = db_update(TBL_SCHEDULE_INSTANCE, array(FIELD_SUMMARY, FIELD_DOCTOR, FIELD_DATE, FIELD_TIME), $new_instance, false, false, $old_instance);
      if ($out != OK_DATA_UPDATE) {
        warn(__FILE__, __FUNCTION__, __LINE__, $out);
        exit(fail_return($out, false));
      }
    }
    return succ_return($out);
  } else {
    $error = ERR_ID_MISSING;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $new);
    exit(fail_return($error, false));
  }
}

function schedule_depopulate($args, $echo = true, $json = true) {
  if (array_key_exists(FIELD_ID, $args)) {
    require_once 'db.php';
    $field_list = get_field_list(__FILE__);
    return db_delete(TBL_SCHEDULE_INSTANCE, array(FIELD_SUMMARY), array(FIELD_SUMMARY => $args[FIELD_ID]), $echo, $json);
  } else {
    $error = ERR_ID_MISSING;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
}

function schedule_populate($summary) {
  $schedule_day_list = get_schedule_day_list($summary);
  return populate_schedule($schedule_day_list, $summary[FIELD_START_DATE], $summary[FIELD_END_DATE],
    $summary[FIELD_TIME], $summary[FIELD_DOCTOR], $summary[FIELD_MAX], $summary[FIELD_ID]);
}

function schedule_populate_from_id($args) {
  if (array_key_exists(FIELD_ID, $args)) {
    require_once 'db.php';
    $field_list = get_field_list(__FILE__);
    $summary_list = db_search(TBL_SCHEDULE, $field_list, $args, false, false);
    if(count($summary_list) == 1) {
      $summary = $summary_list[0];
      schedule_populate($summary);
    } else {
      $error = ERR_NON_SINGLE_RECORD;
      warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
      exit(fail_return($error, false));
    }
  } else {
    $error = ERR_ID_MISSING;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
}

function populate_schedule($day_list, $start_date, $end_date, $time, $doctor, $max, $id) {
  $val_list_of_list = array();
  if(count($day_list) == 0) {
    array_push($val_list_of_list, 
      array(FIELD_SUMMARY => $id, FIELD_DOCTOR => $doctor, FIELD_DATE => $start_date, FIELD_TIME => $time, 
            FIELD_MAX => $max, FIELD_STATUS => 0, FIELD_PATIENT => 0));
  } else {
    foreach ($day_list as $day) {
      $date_list = get_future_date_list_between_two_days($start_date, $end_date, $day);
      foreach ($date_list as $date) {
        array_push($val_list_of_list, 
          array(FIELD_SUMMARY => $id, FIELD_DOCTOR => $doctor, FIELD_DATE => $date, FIELD_TIME => $time, 
          FIELD_MAX => $max, FIELD_STATUS => 0, FIELD_PATIENT => 0));
      }
    }
  }
  return db_multi_insert(TBL_SCHEDULE_INSTANCE, get_field_list("schedule_instance"), $val_list_of_list);
}


function get_future_date_list_between_two_days($start_date, $end_date, $day) {
  //if (strtotime("today", strtotime($start_date)) < strtotime("today")) {
  if (strtotime($start_date) <= strtotime("today")) {
    if($day != strtolower(date("D"))) {
      $start_date = strtotime("next $day");
    } else {
      $start_date = strtotime("today");
    }
  } else {
    $start_date = strtotime("next $day", strtotime($start_date));
  }
  $end_date = strtotime($end_date);
  $date_arr = array();
  
  while($start_date <= $end_date) {
    array_push($date_arr, date('Y-m-d', $start_date));
    $start_date += (7 * 24 * 3600); // add 7 days
  }
  return($date_arr); 
} 


function get_schedule_day_list($summary) {
  $seven_days = array(FIELD_DAY_SUN, FIELD_DAY_MON, FIELD_DAY_TUE, FIELD_DAY_WED, FIELD_DAY_THU, FIELD_DAY_FRI, FIELD_DAY_SAT);
  $schedule_day_list = array();
  foreach ($seven_days as $day) {
    if ($summary[$day] == 1) {
      array_push($schedule_day_list, $day);
    }
  }
  return $schedule_day_list;
}
?>