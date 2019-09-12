<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';
require_once dirname(__FILE__).'/util_sale.php';

info(__FILE__, __FUNCTION__, __LINE__, $action, $request);

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;

switch ($action) { //$action variable defined in session.php
  case ACTION_FIND:
    sale_read($args);
    break;
  case ACTION_READ:
    sale_read($args);
    break;
  case ACTION_MOD:
    sale_update($args);
    break;
  case ACTION_ADD:
    sale_add($args);
    break;
  case ACTION_DEL:
    sale_del($args);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}
?>