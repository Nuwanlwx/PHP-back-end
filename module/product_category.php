<?php
require_once dirname(__FILE__).'/session.php';

debug(__FILE__, __FUNCTION__, __LINE__, $action, $request);

switch ($action) { //$action variable defined in session.php
  case ACTION_FIND:
    product_category_read($request);
    break;
  case ACTION_READ:
    product_category_read($request);
    break;
  case ACTION_MOD:
    product_category_update($request);
    break;
  case ACTION_ADD:
    product_category_add($request);
    break;
  case ACTION_DEL:
    product_category_del($request);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $request);
}

function product_category_add() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }

  require_once 'db.php';
  return db_insert(TBL_PRODUCT_CATEGORY, $field_list, $args);
}

function product_category_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_PRODUCT_CATEGORY, $field_list, $arg, false);
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
    return db_delete(TBL_PRODUCT_CATEGORY, $field_list, $args);
  }
}

function product_category_read() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $args[FIELD_ORDER_BY] = "id";
  info(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);
  require_once 'db.php';
  return db_read(VIEW_PRODUCT_CATEGORY, $args);
}

function product_category_update() {
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
  return db_update(TBL_PRODUCT_CATEGORY, array(FIELD_ID), $args);
}
?>