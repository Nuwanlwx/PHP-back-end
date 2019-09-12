<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';
require_once dirname(__FILE__).'/util_product.php';

// debug(__FILE__, __FUNCTION__, __LINE__, $action, $request);

switch ($action) { //$action variable defined in session.php
  case ACTION_FIND:
    product_read($request);
    break;
  case ACTION_READ:
    product_read($request);
    break;
  case ACTION_MOD:
    product_update($request);
    break;
  case ACTION_ADD:
    product_add($request);
    break;
  case ACTION_DEL:
    product_del($request);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $request);
}
?>
