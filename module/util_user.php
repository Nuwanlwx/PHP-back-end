<?php
function user_add($args) {
  $field_list = get_field_list(__FILE__);
  
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  if(isset($args[FIELD_PASS])) {
    $args[FIELD_PASS] = pass_encrypt($args[FIELD_PASS]);
    $args[FIELD_USER] = strtolower($args[FIELD_USER]);
  }
  require_once 'db.php';
  debug(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  $user_add_resp = db_insert(TBL_USER, $field_list, $args);
  debug(__FILE__, __FUNCTION__, __LINE__, $user_add_resp);
  $user_id = db_get_last_insert_id();
  debug(__FILE__, __FUNCTION__, __LINE__, $user_id);
  if (!$user_id) {
    $user_list = db_search(TBL_USER, $field_list, array(FIELD_USER => $user_id));
    if (sizeof($user_list) == 1) {
      $user_id = $user_list[0][FIELD_ID];
    } else if (sizeof($user_list > 1)) {
      exit(fail_return(ERR_MULTY_MATCH, false));
    } else {
      // This code segment cannot be matched, if no user exist user will be created and user ID will be available
      error(__FILE__, __FUNCTION__, __LINE__, $user_list);
    }
  }
  $_SESSION["newly_added_user_id"] = $user_id;
  $user_para_args = remove_fields($args, $field_list);
  $user_para_field_list = get_field_list(TBL_USER_PARA);
  unset($user_para_args[FIELD_ACTION]);
  foreach ($user_para_args as $user_para => $user_val) {
    $user_info_val_set = array (
      FIELD_USER_ID => $user_id, 
      FIELD_PARA => $user_para, 
      FIELD_VAL => $user_val
    );
    db_insert(TBL_USER_PARA, $user_para_field_list, $user_info_val_set, false, true);
    
  }
  return $user_add_resp;
}

function user_del($args) {
  $field_list = get_field_list(__FILE__);
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        if(isset($arg[FIELD_PASS]) && $arg[FIELD_PASS]) {
          $arg[FIELD_PASS] = pass_encrypt($arg[FIELD_PASS]);
          $args[FIELD_USER] = strtolower($args[FIELD_USER]);
        }
        require_once 'db.php';
        db_delete(TBL_USER, $field_list, $arg, false);
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
    if(isset($args[FIELD_PASS])) {
      $args[FIELD_PASS] = pass_encrypt($args[FIELD_PASS]);
      $args[FIELD_USER] = strtolower($args[FIELD_USER]);
    }
    return db_delete(TBL_USER, $field_list, $args);
  }
}

function user_read($args) {
  require_once 'db.php';
  if (isset($args[FIELD_USER])) $args[FIELD_USER] = strtolower($args[FIELD_USER]);
  return db_read(TBL_USER, $args);
}

function user_search($args) {
  $field_list = get_field_list(__FILE__);
  require_once 'db.php';
  if (isset($args[FIELD_USER])) $args[FIELD_USER] = strtolower($args[FIELD_USER]);
  return db_search(TBL_USER, $field_list, $args);
}

function user_update($args) {
  $field_list = get_field_list(__FILE__);
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  unset($args[FIELD_PASS]);
  $args[FIELD_USER] = strtolower($args[FIELD_USER]);
  require_once 'db.php';
  return db_update(TBL_USER, $field_list, $args);
}
?>