<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__).'/session.php';

function sale_add($args, $echo = true, $json = true) {
  $field_list = get_field_list(__FILE__);
  
  if (!isset($args[FIELD_CUST_ID]) || !$args[FIELD_CUST_ID]) {
    $args[FIELD_CUST_ID] = $_SESSION[FIELD_ID];
  }
  $temp_cust_id = $args[FIELD_CUST_ID];

  if (!isset($args[FIELD_SALE_DATE]) || !$args[FIELD_SALE_DATE]) {
    $args[FIELD_SALE_DATE] = date("Y-m-d H:i:s");
  }
  
  $args[FIELD_FITTER_ID]= $args[FIELD_CUST_ID];

  update_product_id_if_missing($args);

  if (!is_set_all($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
    
  // Function to get all the mobile number rows from tbl_user_para
  $input_cus_id = $args[FIELD_CUST_ID];
  debug(__FILE__, __FUNCTION__, __LINE__, $args);
  // TOOD: This is a quick fix. Permenent fix: API should send "name" insteam of "customer_name"
  if (!isset($args[FIELD_NAME])) $args[FIELD_NAME] = $args["customer_name"];

  // TOOD: This is a quick fix. Permenent fix: API should send "telephone" insteam of "mobile"
  if (!isset($args[FIELD_TEL])) $args[FIELD_TEL] = $args["mobile"];
  
  if($args[FIELD_NAME] != "-" && $args[FIELD_TEL] != "-") {
    $search_specs = array(FIELD_PARA =>FIELD_TEL, FIELD_VAL => $args[FIELD_TEL]);
    $user_list = db_read(TBL_USER_PARA, $search_specs, false, false);
  
    if (sizeof($user_list) == 1) {
      $args[FIELD_CUST_ID] = $user_list[0][FIELD_USER_ID];
    } else if (sizeof($user_list) < 1) {
      // User doesn't exists
      require_once 'util_user.php';
      
      $user_specs = array(
        FIELD_TYPE => "user", 
        FIELD_COMMENT => "add by the system", 
        FIELD_TEL => $args[FIELD_TEL], 
        FIELD_DISTRICT => "COLOMBO", 
        FIELD_ACTION => ACTION_ADD, 
        FIELD_NAME_FIRST => $args[FIELD_NAME],
        FIELD_USER => $args[FIELD_TEL], 
        FIELD_PASS => "systemUser", 
        FIELD_ADD_TYPE => "self_reg"
      );

      user_add($user_specs);
      $args[FIELD_CUST_ID] = $_SESSION["newly_added_user_id"];
    } else {
      // Multi user match; 
      exit(fail_return(ERR_MULTY_MATCH, false));
    }
  }
  $args["fitter_id"] = $input_cus_id;
  $sale_add_resp = db_insert(TBL_SALE, $field_list, $args, $echo, $json);
  $sale_id = db_get_last_insert_id();
    
  $sale_followup_args = remove_fields($args, $field_list);
    
  $sale_followup_field_list = get_field_list(TBL_SALE_FOLLOWUP);
    
  $removeField = array (0=>FIELD_FOLLOWUP_TS, 1=>FIELD_ID, 2=>FIELD_SESSION_ID);
  $sale_followup_field_list = remove_tbl_fields($sale_followup_field_list, $removeField);

  unset($sale_followup_args[FIELD_ACTION]);
  unset($sale_followup_args[FIELD_SESSION_ID]);
  foreach ($sale_followup_args as $sale_followup_para => $sale_followup_val) {
    $sale_followup_info_val_set = array (
      FIELD_SALE_ID => $sale_id,    
      FIELD_PARA => $sale_followup_para, 
      FIELD_VAL => $sale_followup_val
    );
    db_insert(TBL_SALE_FOLLOWUP, $sale_followup_field_list, $sale_followup_info_val_set, false, true);
  }
  return $sale_add_resp;
}

function sale_del() {
  $args = func_get_args();
  $field_list = array(FIELD_ID);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  if (!is_set_any($args, $field_list)) {
    $succ_count = 0;
    foreach ($args as $arg) {
      if (is_array($arg) && is_set_any($arg, $field_list)) {
        db_delete(TBL_SALE, $field_list, $arg, false);
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
    return db_delete(TBL_SALE, $field_list, $args);
  }
}

function sale_read() {
  $field_list = get_field_list(__FILE__);
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  $args[FIELD_ORDER_BY] = "id";

  if (!isset($args[FIELD_CUST_ID]) || !$args[FIELD_CUST_ID]) $args[FIELD_CUST_ID] = $_SESSION[FIELD_ID];
  info(__FILE__, __FUNCTION__, __LINE__, $field_list, $args);

  $args_to_db = $args;

  if($_SESSION[FIELD_TYPE] == USER_TYPE_FITTER) {
    $args_to_db = array(
      FIELD_SESSION_ID => $args[FIELD_SESSION_ID],
      FIELD_ACTION => $args[FIELD_ACTION],
      FIELD_ORDER_BY => $args[FIELD_ORDER_BY],
      FIELD_FITTER_ID => $args[FIELD_CUST_ID]
    );
  }
  
  require_once 'db.php';
  $sale_list = db_read(TBL_SALE, $args_to_db, false, false);

  $followup_sale_list = array();
  foreach ($sale_list as $sale) {
    $sale_id = $sale[FIELD_ID];
    $followup_list = db_read(TBL_SALE_FOLLOWUP, array(FIELD_SALE_ID => $sale_id), false, false);
    foreach ($followup_list as $followup) {
      $sale[$followup[FIELD_PARA]] = $followup[FIELD_VAL];
    }
    array_push($followup_sale_list, $sale);
  }
  return succ_return($followup_sale_list, true, true, count($followup_sale_list));
}

function sale_update() {
  $args = func_get_args();
  $field_list = get_field_list(__FILE__);
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!is_set_any($args, $field_list)) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $args, $field_list);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  
  $sale_followup_args = remove_fields($args, $field_list);
    
  $sale_followup_field_list = get_field_list(TBL_SALE_FOLLOWUP);
    
  $removeField = array (0=>FIELD_FOLLOWUP_TS,1=>FIELD_ID);
  $sale_followup_field_list = remove_tbl_fields($sale_followup_field_list, $removeField);

  unset($sale_followup_args[FIELD_ACTION]);
  foreach ($sale_followup_args as $sale_followup_para => $sale_followup_val) {
    $sale_followup_info_val_set = array (
      FIELD_SALE_ID => $args[FIELD_ID],    
      FIELD_PARA => $sale_followup_para
    );
    db_delete(TBL_SALE_FOLLOWUP, array(FIELD_SALE_ID, FIELD_PARA), $sale_followup_info_val_set, false, true);
    
    $sale_followup_info_val_set[FIELD_VAL] = $sale_followup_val;
    db_insert(TBL_SALE_FOLLOWUP, $sale_followup_field_list, $sale_followup_info_val_set, false, true);
  }
  
  return db_update(TBL_SALE, array(FIELD_ID), $args);
} 

function update_product_id_if_missing(&$args) {
  if (!(isset($args[FIELD_PROD_ID]) && $args[FIELD_PROD_ID])) {
    // Let's see whether we have product name and thread pattern
    if (isset($args[FIELD_PROD_NAME]) && $args[FIELD_PROD_NAME]) {
      // Let's search the product by name
      $prod_specs = array(FIELD_NAME => $args[FIELD_PROD_NAME]);
      require_once 'db.php';
      $product_list = db_read(TBL_PRODUCT, $prod_specs, false, false);
      if (sizeof($product_list) == 1) {
        // Only one matching products
        $args[FIELD_PROD_ID] = $product_list[0][FIELD_ID];
      } else if (sizeof($product_list > 1)) {
        // Multiple matching products; let's tyre thread pattern
        if (isset($args[FIELD_PATTERN]) && $args[FIELD_PATTERN]) {
          // Let's get all matching products
          $prod_specs[FIELD_PATTERN] = $args[FIELD_PATTERN];
          $matching_list = array();
          foreach ($product_list as $product) {
            if ($args[FIELD_PATTERN] == $product[FIELD_PATTERN]) array_push($matching_list, $product);
          }
          if (sizeof($matching_list) == 1) {
            $args[FIELD_PROD_ID] = $matching_list[0][FIELD_ID];
          } else if (sizeof($matching_list) < 1) {
            // Else ($matching_list < 1); No matching products; Nothing to do
            warn(__FILE__, __FUNCTION__, __LINE__, ERR_NO_MATCH, $prod_specs, $product_list);    
          } else {
            //Multiple products matching; Nothing to do
            warn(__FILE__, __FUNCTION__, __LINE__, ERR_MULTY_MATCH, $prod_specs, $product_list);  
          }
        } else {
          //Tyre thread pattern is not defined; Nothing to do
          warn(__FILE__, __FUNCTION__, __LINE__, ERR_MULTY_MATCH, $prod_specs, $product_list);
        }
      } else {
        // Else ($product_list < 1); No matching products; Nothing to do
        warn(__FILE__, __FUNCTION__, __LINE__, ERR_NO_MATCH, $prod_specs, $product_list);
      }
    } else {
      // FIELD_PROD_NAME is not defined; Nothing to do
      warn(__FILE__, __FUNCTION__, __LINE__, ERR_PARA_NOT_DEFINED, FIELD_PROD_NAME, $args);
    }
  } // Else product id is already defined; Do nothing
}
?>