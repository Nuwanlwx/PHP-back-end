<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';
require_once dirname(__FILE__).'/util_user.php';

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;
  
if (!isset($_SESSION[FIELD_TYPE]) || ($_SESSION[FIELD_TYPE] != USER_TYPE_ADMIN)) {
  if ($action == ACTION_ADD) {
    !isset($args[FIELD_ADD_TYPE]) && $args[FIELD_ADD_TYPE] = "self_reg";
    (!isset($args[FIELD_TYPE]) || !$args[FIELD_TYPE]) && $args[FIELD_TYPE] = "user";
  } else if (in_array($action, array(ACTION_MOD, ACTION_DEL))) {
    $error = ERR_PERMISSION_DENIED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error);
    exit(fail_return($error, false));
  }
}

switch ($action) { //$action variable defined in session.php
  case ACTION_ADD:
    user_add($args);
    break;
  case ACTION_DEL:
    user_del($args);
    break;
  case ACTION_MOD:
    user_update($args);
    break;
  case ACTION_READ:
    user_read($args);
    break;
  case ACTION_FIND:
    user_search($args);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}
?>