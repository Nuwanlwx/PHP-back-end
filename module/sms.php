<?php
// Bypassing Access Control Allow Origin
// Ref: https://stackoverflow.com/questions/7564832/how-to-bypass-access-control-allow-origin
header("Access-Control-Allow-Origin: *");

// Validate user session
require_once dirname(__FILE__).'/session.php';
require_once dirname(__FILE__).'/util_sale.php';

// Sample URL call to test
// http://localhost:81/back/module/sms.php?user=admin&pass=test123&src=(+94%2071-620%200000)&action=add&dst=0112233456&msg=nimesh%2c0720005555%2cc%2cgj7200%2c15000%2c90/90-10%2cfr%2c1%2c151%2cZOOM-D

info(__FILE__, __FUNCTION__, __LINE__, $request);

$args = $_REQUEST;
$args = (sizeof($args) == 1) ? $args[0] : $args;

// Let's extract user mobile and message from the request
$msg = isset($args[SMS_MSG]) ? $args[SMS_MSG] : isset($args[SMS_RESP]) ? $args[SMS_RESP] : "";
$src = isset($args[SMS_SRC]) ? $args[SMS_SRC] : isset($args[SMS_SOURCE]) ? $args[SMS_SOURCE] : "";

// Neither $msg nor $src can be empty
if (!$msg || !$src) {
  warn(__FILE__, __FUNCTION__, __LINE__, ERR_PARA_NOT_DEFINED, $msg, $src, $args);
  exit(fail_return(ERR_PARA_NOT_DEFINED, false));
}

// Get the sales details as an associative array
$sale_details = extract_sales_details_from_sms_msg($msg);

// Get the originated mobile (from number/src) formatted
$sale_details[SMS_SRC] = format_phone_num($src);

// Let's have original SMS also saved for later use in case
$sale_details[SMS_MSG] = $msg;

// Check whether sales details are OK
if (validate_sale_details($sale_details)) {
  // Let's add the sale
  $sale_details[SMS_RESP] .= " - " . sale_add($sale_details, false, false);

  // Send an SMS to the user based on add sale response
  send_sms_response($sale_details);
} else {
  // Send an SMS to the user with corrective instructions
  send_sms_response($sale_details);
}

// Validate SMS sales details before processing;
// Note the sale details are passed by reference. 
// So the changes to the sale_details within the function is effective outside
function validate_sale_details(&$sale_details) {
  //category shortforms
  $catagory_list = $GLOBALS['SMS_PROD_CAT'];
  $sms_cat = $sale_details[FIELD_CATEGORY];
  $sale_details[FIELD_CATEGORY] = (isset($catagory_list[$sms_cat])) ? $catagory_list[$sms_cat] : "";

  //check position is valid or not
  $position_list = $GLOBALS['SMS_PROD_POS'];
  $sms_pos = $sale_details[FIELD_POSITION];

  $sale_details[FIELD_POSITION] = (isset($position_list[$sms_pos])) ? $position_list[$sms_pos] : "";

  $agent_id = (isset($sale_details[FIELD_AGENT_ID])) ? $sale_details[FIELD_AGENT_ID] : "";
  if ($agent_id) {
    require_once 'db.php';
    $agent_list = db_read(TBL_AGENT, array(FIELD_ID => $agent_id), false, false);

    if (sizeof($agent_list) < 1) {
      // Search submitted SMS agent ID in Agent's name
      $agent_list = db_read(TBL_AGENT, array(FIELD_NAME => $agent_id), false, false);
      if (sizeof($agent_list) == 1) {
        // Found the Agent ID by Name
        $sale_details[FIELD_AGENT_ID] = $agent_list[0][FIELD_ID];
      } else if (sizeof($agent_list) < 1) {
        // No matching Agent
        warn(__FILE__, __FUNCTION__, __LINE__, ERR_DB_INCORRECT_ROW_COUNT, $agent_list, $agent_id);
        $sale_details[FIELD_AGENT_ID] = "";
      } else {
        // Multi agent accounts are matching
        warn(__FILE__, __FUNCTION__, __LINE__, ERR_DB_INCORRECT_ROW_COUNT, $agent_list, $agent_id);
        $sale_details[FIELD_AGENT_ID] = "";
      }
    } else if (sizeof($agent_list) > 1) {
      // Multi agent accounts are matching
      warn(__FILE__, __FUNCTION__, __LINE__, ERR_DB_INCORRECT_ROW_COUNT, $agent_list, $sale_details);
      $sale_details[FIELD_AGENT_ID] = "";
    }
  }

  // Update product ID if missing
  update_product_id_if_missing($sale_details);

  // Let's set Unit price to one (1) if not provided
  $unit_price = $sale_details[FIELD_UNIT_PRICE];
  $sale_details[FIELD_UNIT_PRICE] = ($unit_price) ? $unit_price : 1;

  // Let's build the error response
  $err_count = 0;
  $resp_sms = "";
  foreach ($GLOBALS['SMS_PARA_ORDER'] as $para) {
    if ($para == FIELD_PROD_NAME) {
      if(isset($sale_details[FIELD_PROD_ID]) && $sale_details[FIELD_PROD_ID]) {
        $resp_sms .= $sale_details[$para] . ",";  
      } else {
        $resp_sms .= "<" . $para . ">,";
        $err_count++;  
      }
    } else if (isset($sale_details[$para]) && $sale_details[$para]) {
      $resp_sms .= $sale_details[$para] . ",";
    } else if ($para == FIELD_COMMENT) {
      $resp_sms .= "<" . $para . ">";
    } else {
      $resp_sms .= "<" . $para . ">,";
      $err_count++;
    }
  }

  $ret_val = true;
  if ($err_count) {
    $resp_sms .= " - $err_count parameter(s) are invalid";
    $ret_val = false;
    info(__FILE__, __FUNCTION__, __LINE__, $ret_val, $err_count, $resp_sms);
  } 
  $sale_details[SMS_RESP] = $resp_sms;
  return $ret_val;
}

// Send user responses in both success and failed cases
function send_sms_response($sale_details) {
  $resp = "";
  if(isset($sale_details[SMS_RESP]) && $sale_details[SMS_RESP]) {
    $resp = $sale_details[SMS_RESP];
    $dst = $sale_details[SMS_SRC];
    require_once SMS_API_MODULE;
    $resp = send_sms($resp, $dst);
    exit(succ_return($resp, false, true, 1));
  } else {
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_PARA_NOT_DEFINED, $sale_details);
    exit(fail_return($resp, false));
  }
}

function extract_sales_details_from_sms_msg($msg) {
  // Let's brake the message by comma
  $msg_para_list = explode(",", $msg);
  $sale_details = array();

  // SMS parameter order can be defined in definition.php (SMS_PARA_ORDER)
  foreach($GLOBALS['SMS_PARA_ORDER'] as $sms_para) {
    switch ($sms_para) {
      // Extract customer name; remove spaces at two ends; tern to Camel Case
      case FIELD_NAME: $sale_details[FIELD_NAME] = ucwords(trim(array_shift($msg_para_list))); break;

      // Extract mobile number and format
      case FIELD_TEL: $sale_details[FIELD_TEL] = format_phone_num(array_shift($msg_para_list)); break;
      
      // Extract Vehicle Category
      case FIELD_CATEGORY: $sale_details[FIELD_CATEGORY] = strtoupper(trim(array_shift($msg_para_list))); break;
      
      // Extract Vehicle Number
      case FIELD_VEHI_NO: $sale_details[FIELD_VEHI_NO] = format_vehicle_num(array_shift($msg_para_list)); break;
      
      // Extract Odometer Reading
      case FIELD_ODOMETER_READING: $sale_details[FIELD_ODOMETER_READING] = trim(array_shift($msg_para_list)); break;
      
      // Extract Tyre Name
      case FIELD_PROD_NAME: $sale_details[FIELD_PROD_NAME] = strtoupper(trim(array_shift($msg_para_list))); break;
      
      // Extract Tyre Possition
      case FIELD_POSITION: $sale_details[FIELD_POSITION] = strtoupper(trim(array_shift($msg_para_list))); break;
      
      // Extract Tyre Quantity
      case FIELD_QTY: $sale_details[FIELD_QTY] = trim(array_shift($msg_para_list)); break;
      
      // Extract Agent ID
      case FIELD_AGENT_ID: $sale_details[FIELD_AGENT_ID] = trim(array_shift($msg_para_list)); break;
      
      // Extract Thread Pattern
      case FIELD_PATTERN: $sale_details[FIELD_PATTERN] = strtoupper(trim(array_shift($msg_para_list))); break;
      
      // Extract Unit Price
      case FIELD_UNIT_PRICE: $sale_details[FIELD_UNIT_PRICE] = trim(array_shift($msg_para_list)); break;
      
      // Let's consider rest of the message as comments if any
      case FIELD_COMMENT: $sale_details[FIELD_COMMENT] = strtoupper(trim(array_shift($msg_para_list))); break;
    }
  }

  // Let's return constructed sale's details
  return $sale_details;
}
?>
