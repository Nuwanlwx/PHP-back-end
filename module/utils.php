<?php
require_once dirname(__FILE__).'/../config/definition.php';
define('LOG_FILE', AUDIT_LOG_DIR.'/'.AUDIT_LOG_FILE_PREFIX.date("Ymd").AUDIT_LOG_FILE_SUFFIX.'.'.AUDIT_LOG_FILE_EXT);

function debug() {
  $args = func_get_args();
  write(array_merge(array('DEBUG'), $args));
}

function info() {
  $args = func_get_args();
  write(array_merge(array('INFO'), $args));
}

function warn() {
  $args = func_get_args();
  write(array_merge(array('WARN'), $args));
}

function error() {
  $args = func_get_args();
  write(array_merge(array('ERROR'), $args));
}

function todo() {
  $args = func_get_args();
  write(array_merge(array('TODO'), $args));
}

function write() {
  $args = func_get_args();
  $i = 0;
  if (sizeof($args) == 1 && is_array($args)) {
    $args = $args[0];
  }
  $print_arr = array();
  foreach ($args as $arg) {
    array_push($print_arr, arg_to_str($arg));
  }
  write_log(date("H:i:s").", ".$_SERVER['REMOTE_ADDR'].", ".session_id().", ".implode(", ", $print_arr)."\r\n", 3, LOG_FILE);
}

function write_log($log, $type, $dest) {
  error_log($log, $type, $dest);
}

function arg_to_str($arg) {
  $str = "";
  if (is_array($arg)) {
    $str .="array(";
    $str_arr = array();
    foreach ($arg as $key => $val) {
      array_push($str_arr, "$key => ".arg_to_str($val));
    }
    $str .= implode(", ", $str_arr);
    $str .= ")";
  } else if (is_object($arg)) {
    $str .= "object(".var_export($arg, true).")";
  } else if (is_resource($arg)) {
    $str .= "resource(".var_export($arg, true).")";
  } else if (is_file($arg)) {
    $str .= basename($arg, ".php");
  } else if (is_bool($arg)) {
    $str .= ($arg) ? 'true' : 'false';
  } else {
    $str .= $arg;
  }
  return $str;
}

function json_return($resp, $echo = false) {
  header('Content-Type: application/json');
  $return = json_encode($resp);
  if($echo) {
    echo $return;
  }
  return $return;
}

function fail_return($details, $echo = true, $json = true, $success = true) {
  if ($json) {
    return json_return(array(JSON_SUCCESS => $success, JSON_STATUS => false, JSON_DETAILS => $details), $echo);
  } else {
    if($echo) echo arg_to_str($details);
    return $details;
  }
}

function succ_return($details, $echo = true, $json = true, $total = 0) {
  if ($json) {
    return json_return(array(JSON_SUCCESS => true, JSON_STATUS => true, JSON_DETAILS => $details, JSON_TOTAL => $total), $echo);
  } else {
	  if($echo) echo arg_to_str($details);
    return $details;
  }
}

function is_set_all($array, $field) {
  if (!is_array($field)) {
    return isset($array[$field]);
  }
  foreach ($field as $key) {
    if(!isset($array[$key]) && !($key == FIELD_ID)) {
      debug(__FILE__, __FUNCTION__, __LINE__, $key);
      return false;
    } mysql_error();
  }
  return true;
}

function is_set_any($array, $field) {
  if (!is_array($field)) {
    return isset($array[$field]);
  }
  foreach ($field as $key) {
    if(isset($array[$key])) return true;
  }
  return false;
}

function array_remove($val, $arr, $preserve = true) {
  if (empty($arr) || !is_array($arr)) { return $arr; }

  foreach(array_keys($arr,$val) as $key){ unset($arr[$key]); }

  return ($preserve) ? $arr : array_values($arr);
}

function create_table_from_list($list) {
  if (!$list) return "";
  $out = "";
  $out .= "<table border='1'>\n";
  $head = array_shift($list);
  $out .= "<tr>";
  if (is_array($head)) {
    foreach ($head as $field) {
      $out .= "<th>$field</th>";
    }
  } else {
    $out .= "<th>$head</th>";
  }
  $out .= "</tr>\n";
  foreach ($list as $row) {
    $out .= "<tr>";
    if (is_array($row)) {
      foreach ($row as $field) {
        $out .= "<td>$field</td>";
      }
    } else {
      $out .= "<td>$row</td>";
    }
    $out .= "</tr>\n";
  }
  $out .= "</table>\n";
  return $out;
}

function tbl_array_from_associate_array($arr) {
  return array_merge(array_keys($arr), array_values($arr));
}

function get_request($request, $post_str) {
  $ret = is_array($request) ? $request : json_decode($request);
  if ($post_str) {
    $decoded = json_decode($post_str, true);
    if(!$decoded) {
      $pairs = explode("&", $post_str);
      $vars = array();
      foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $decoded[$name] = $value;
      }
    }
    switch (gettype($decoded)) {
      case "NULL":
        break;
      case "array":
        $ret = array_merge($ret, $decoded);
        break;
      default:
        $ret = array_merge($ret, (array) $decoded);
        break;
    }
  }
  return unique_sort($ret);
}

function get_field_list($module) {
  //echo $module."Test";
  
  if (is_file($module)) {
    $module = basename($module, ".php");
  }
  if (!isset($_SESSION['field_list'][$module])) {
    switch ($module) {
      
      case 'product':
	  case 'product2':
      case 'tbl_product':
        //id name brand pr pattern made_in category radial tubeless width profile diameter
        //$_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_BRAND, FIELD_PR, FIELD_PATTERN, FIELD_MADE_IN, 
          //FIELD_CATEGORY, FIELD_RADIAL, FIELD_TUBELESS, FIELD_WIDTH, FIELD_PROFILE, FIELD_DIAMETER, FIELD_LIST_PRICE, FIELD_IMAGE, FIELD_LD_INDEX_SINGLE, FIELD_LD_INDEX_DUAL, FIELD_SPEED_RATING);
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_CEAT, FIELD_NAME, FIELD_BALLOON, FIELD_RIM, FIELD_APPLICATION, FIELD_BRAND, FIELD_PATTERN, FIELD_TUBELESS, FIELD_PR, FIELD_SPEED_RATING, FIELD_OD, FIELD_SW, FIELD_NSD, FIELD_RIM_WIDTH, FIELD_LD_INDEX_SINGLE, FIELD_LD_INDEX_DUAL, FIELD_MAX_AIR_SINGLE_KPA, FIELD_MAX_AIR_SINGLE_PSI,FIELD_MAX_AIR_DUAL_KPA, FIELD_MAX_AIR_DUAL_PSI, FIELD_FEATURES, FIELD_BENEFITS, FIELD_CATEGORY);
        break;
      case 'customer':
      case 'tbl_customer':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_DESCRIPTION, FIELD_PARENT);
        break;
      case 'feedback':
      case 'tbl_feedback':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_CUST_ID, FIELD_NAME, FIELD_TEL, FIELD_COMMENT, FIELD_TIMESTAMP);
        break;
      case 'category':
      case 'tbl_category':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_DESCRIPTION, FIELD_PARENT);
        break;
      case 'view_category':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_DESCRIPTION, FIELD_PARENT, FIELD_NODE_TYPE);
        break;
      case 'product_category':
      case 'tbl_product_category':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_PROD_ID, FIELD_CATEGORY_ID);
        break;
      case 'view_product_category':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_PROD_ID, FIELD_CATEGORY_ID, FIELD_TOP_CATEGORY, FIELD_SUB_CATEGORY, 
          FIELD_PROD_NAME, FIELD_PROD_BRAND, FIELD_LIST_PRICE);
        break;
      case 'sale_followup':
      case 'tbl_sale_followup':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_SALE_ID, FIELD_FOLLOWUP_TS, FIELD_PARA, FIELD_VAL);
        break;
      case 'sale':
      case 'tbl_sale':
      case 'util_sale':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_CUST_ID, FIELD_PROD_ID, FIELD_QTY, FIELD_SALE_DATE, FIELD_AGENT_ID, FIELD_VEHI_NO, FIELD_UNIT_PRICE, FIELD_FITTER_ID);
        break;
      case 'agent':
      case 'agent2':
      case 'tbl_agent':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_ADDRESS, FIELD_LAT, FIELD_LON, FIELD_EMAIL, 
          FIELD_TEL, FIELD_USER_ID);
        break;
      case 'officer':
      case 'tbl_officer':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_EMP_ID, FIELD_EMAIL, FIELD_TEL, FIELD_USER_ID);
        break;
      case 'appointment':
      case 'tbl_appointment':
        //id schedule doctor 	time 	appointment patient telephone nic doc_fee 	inst_fee 	status 	comment
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_SCHEDULE, FIELD_DOCTOR, FIELD_DATE, FIELD_TIME, FIELD_APPOINTMENT, 
          FIELD_PATIENT, FIELD_ADDRESS, FIELD_TEL, FIELD_NIC, FIELD_CHARGE, FIELD_INST_CHARGE, FIELD_STATUS, FIELD_PAYTIME, 
          FIELD_COMMENT, FIELD_REFUND, FIELD_INST_REFUND, FIELD_REFUNDTIME, FIELD_PRINTED, FIELD_REF, FIELD_VAT, FIELD_ROUND_OFF,
		  FIELD_VAT_REFUND, FIELD_ROUND_REFUND);
        break;
      case 'doctor':
      case 'tbl_doctor':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_NAME, FIELD_SPECIALITY, FIELD_CHARGE, FIELD_INST_CHARGE, 
          FIELD_TEL, FIELD_SLMC, FIELD_VAT, FIELD_ROUND_OFF);
        break;
      case 'schedule_instance':
      case 'tbl_schedule_instance':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_SUMMARY, FIELD_DOCTOR, FIELD_DATE, FIELD_TIME, FIELD_MAX, FIELD_PATIENT, FIELD_STATUS);
        break;
      case 'schedule':
      case 'tbl_schedule':
      case 'schedule_summary':
      case 'tbl_schedule_summary':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_DOCTOR, FIELD_START_DATE, FIELD_TIME, 
          FIELD_DAY_SUN, FIELD_DAY_MON, FIELD_DAY_TUE, FIELD_DAY_WED, FIELD_DAY_THU, FIELD_DAY_FRI, FIELD_DAY_SAT, 
          FIELD_END_DATE, FIELD_MAX);
        break;
      case 'access':
      case 'user':
      case 'tbl_user':
      case 'util_user':
      //echo $module;
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_USER, FIELD_PASS, FIELD_TYPE, FIELD_COMMENT);
       // print_r($_SESSION);
        break;
      case 'user_para':
      case 'tbl_user_para':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_USER_ID, FIELD_PARA, FIELD_VAL);
        break;
      case 'promotion':
      case 'tbl_promotion':
        $_SESSION['field_list'][$module] = array(FIELD_ID, FIELD_TITLE, FIELD_BODY);
        break;
    }
  }
  
  return $_SESSION['field_list'][$module];
  
}

function unique_sort($arr) {
  $ret = array();
  foreach ($arr as $key => $val) {
    if (!array_key_exists($key, $ret)) {
      $ret[$key] = $val;
    }
  }
  asort($ret);
  return $ret;
} 

function pass_encrypt($pass) {
  return base64_encode($pass);
}

function remove_fields ($key_val_list, $key_list_to_remove) {
  foreach ($key_list_to_remove as $key_to_remove) {
    // debug(__FILE__, __FUNCTION__, __LINE__, $key_to_remove);
    unset($key_val_list[$key_to_remove]);
  }
  return $key_val_list;
}

function remove_tbl_fields ($key_val_list, $key_list_to_remove) {
  foreach ($key_list_to_remove as $key_to_remove) {
    // debug(__FILE__, __FUNCTION__, __LINE__, $key_to_remove);
      foreach($key_val_list as $key=>$key_val){
          if($key_val == $key_to_remove){
              unset($key_val_list[$key]);
          }
      }
  }
  return $key_val_list;
}

function format_phone_num ($str_num) {
  // let's remove all spaces, dashes and brackets in the given number (E.g. 077 378 5550, 077-378-5550)
  $str_num = preg_replace("/[\s-\(\)]+/", "", $str_num);
  // If the number is 0773785550, +94773785550 or 0094773785550 format let's remove leading zero & +
  $str_num = ltrim($str_num, "0+");
  // Let's add 94 in front if number length is 9 (E.g 773785550)
  $str_num = (strlen($str_num) == 9) ? "94".$str_num : $str_num;
  // Return formatted number
  return $str_num;
}

function format_vehicle_num ($vehicle_num) {
  // Remove spaces from two ends and replace multiple spaces and dashes with a single space
  $vehicle_num = strtoupper(preg_replace("/[\s-]+/", " ", trim($vehicle_num)));
  // Return formatted number
  return $vehicle_num;
}
?>