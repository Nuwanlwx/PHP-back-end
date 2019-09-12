<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session2.php';

// debug(__FILE__, __FUNCTION__, __LINE__, $action, $request);

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;
switch ($action) { //$action variable defined in session.php
  case ACTION_READ:
    promotion_read($args);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_REQUEST);
}



function promotion_read() {
  $json_file = file_get_contents('../promotion_posts/promotions.json');
  $json_dec = json_decode($json_file, true);

  return succ_return($json_dec, true, true);
}


?>