<?php
require_once dirname(__FILE__).'/config.php';

date_default_timezone_set(TIME_ZONE);

define('ACTION_ADD', 'add');
define('ACTION_BACKUP', 'backup');
define('ACTION_CLEAN', 'clean');
define('ACTION_CLOSEBY', 'closeby');
define('ACTION_DEL', 'remove');
define('ACTION_FIND', 'search');
define('ACTION_LOGIN', 'login');
define('ACTION_LOGOUT', 'logout');
define('ACTION_MOD', 'update');
define('ACTION_PASSWD', 'passwd');
define('ACTION_POPULATE', 'populate');
define('ACTION_READ', 'read');
define('ACTION_REFUND', 'refund');
define('ACTION_RESTORE', 'restore');
define('ACTION_SPECIALITY', 'speciality');

define('AUDIT_LOG_FILE_EXT', 'log');
define('AUDIT_LOG_FILE_PREFIX', '');
define('AUDIT_LOG_FILE_SUFFIX', '_audit');

define('ERR_API', "API Error");
define('ERR_AUTHENTICATION', "Unauthorized access attempt.");
define('ERR_ACTION_NOT_DEFINED', "Action is not defined");
define('ERR_CURRENT_PASS_NA', "The current password is not given");

define('ERR_DB_BACKUP', "DB backup failed");
define('ERR_DB_CONNECT', "Cannot connect to DB");
define('ERR_DB_DELETE', "Data deletion failed");
define('ERR_DB_DELETE_NO_CONDITION', "No condition given to remove data");
define('ERR_DB_INCORRECT_ROW_COUNT', "Unexpected number of rows");
define('ERR_DB_INSERT', "Cannot insert data to DB");
define('ERR_DB_INSERT_DUPLICATE', "Duplicate data");
define('ERR_DB_READ', "Cannot read data from DB");
define('ERR_DB_RESTORE', "DB restore failed");
define('ERR_DB_UNKNOWN', "Unknown DB Error");
define('ERR_DB_UPDATE', "Cannot update DB");
define('ERR_DB_UPDATE_NO_DATA', "No data given to update");
define('ERR_DB_UPDATE_NO_ID', "Cannot update due to missing ID");

define('ERR_FILE_NOT_EXISTS', "The file does not exists");
define('ERR_ID_MISSING', "Missing record ID");
define('ERR_MAX_APPOINTMENT', "Met the maximum possible appointments");
define('ERR_MULTY_MATCH', "Matching Multiple Items");
define('ERR_NO_MATCH', "No Matching Items");
define('ERR_NON_SINGLE_RECORD', "This operation permits exatly with one record");
define('ERR_NEW_PASS_NA', "The new password is not given");
define('ERR_NEW_PASS_NOT_MATCH', "The new password and verification are not matching");
define('ERR_NON_PERMITTED_USERNAME', "Not permitted username");
define('ERR_PARA_NOT_DEFINED', "One or more parameters are not defined");
define('ERR_PERMISSION_DENIED', "Permission denied");
define('ERR_UNKNOWN', "Unknown error");
define('ERR_UNKNOWN_ACTION', "Unknown action");

// FIELD_TOP_CATEGORY, FIELD_SUB_CATEGORY, FIELD_PRODUCT_NAME, FIELD_PRODUCT_BRAND, FIELD_PRODUCT_LIST_PRICE
define('FIELD_ACTION', 'action');
define('FIELD_ADD_TYPE', 'add_type');
define('FIELD_ADDRESS', 'address');
define('FIELD_AGENT_ID', 'agent_id');
define('FIELD_APPLICATION', 'application');
define('FIELD_APPOINTMENT', 'appointment');
define('FIELD_ARCHIVE', 'archive');
define('FIELD_BALLOON', 'balloon');
define('FIELD_BENEFITS', 'benefits');
define('FIELD_BRAND', 'brand');
define('FIELD_BODY', 'body');
define('FIELD_CATEGORY', 'category');
define('FIELD_CATEGORY_ID', 'category_id');
define('FIELD_CEAT', 'ceat');
define('FIELD_CHARGE', 'charge');
define('FIELD_COMMENT', 'comment');
define('FIELD_CUST_ID', 'customer_id');
define('FIELD_DATE', 'date');
define('FIELD_DAY_SUN', 'sun');
define('FIELD_DAY_MON', 'mon');
define('FIELD_DAY_TUE', 'tue');
define('FIELD_DAY_WED', 'wed');
define('FIELD_DAY_THU', 'thu');
define('FIELD_DAY_FRI', 'fri');
define('FIELD_DAY_SAT', 'sat');
define('FIELD_DESCRIPTION', 'description');
define('FIELD_DIAMETER', 'diameter');
define('FIELD_DISTRICT', 'district');
define('FIELD_DOCTOR', 'doctor');
define('FIELD_EMAIL', 'email');
define('FIELD_EMP_ID', 'emp_id');
define('FIELD_END_DATE', 'end_date');
define('FIELD_FEATURES', 'features');
define('FIELD_FILE', 'file');
define('FIELD_FITTER_ID', 'fitter_id');
define('FIELD_FOLLOWUP_TS', 'followup_ts');
define('FIELD_ID', 'id');
define('FIELD_IMAGE', 'image');
define('FIELD_INST_CHARGE', 'inst_charge');
define('FIELD_INST_REFUND', 'inst_refund');
define('FIELD_LAT', 'lat');
define('FIELD_LD_INDEX_SINGLE', 'ld_index_single');
define('FIELD_LD_INDEX_DUAL', 'ld_index_dual');
define('FIELD_LIST_PRICE', 'list_price');
define('FIELD_LON', 'lon');
define('FIELD_MADE_IN', 'made_in');
define('FIELD_MAX', 'max');
define('FIELD_MAX_AIR_SINGLE_KPA', 'max_air_single_kpa');
define('FIELD_MAX_AIR_SINGLE_PSI', 'max_air_single_psi');
define('FIELD_MAX_AIR_DUAL_KPA', 'max_air_dual_kpa');
define('FIELD_MAX_AIR_DUAL_PSI', 'max_air_dual_psi');
define('FIELD_MONTH', 'Month');
define('FIELD_MOBILE_NUMBER', 'mobile_number');
define('FIELD_NAME', 'name');
define('FIELD_NAME_FIRST', 'first_name');
define('FIELD_NAME_LAST', 'last_name');
define('FIELD_NIC', 'nic'); 
define('FIELD_NODE_TYPE', 'node_type');
define('FIELD_NSD', 'nsd');
define('FIELD_OD', 'od');
define('FIELD_ODOMETER_READING', 'odometer_reading');
define('FIELD_ORDER_BY', 'order_by'); 
define('FIELD_PARA', 'para');
define('FIELD_PARENT', 'parent');
define('FIELD_PASS', 'pass'); 
define('FIELD_PASS1', 'pass1'); 
define('FIELD_PASS2', 'pass2');
define('FIELD_PATIENT', 'patient');
define('FIELD_PATIENT_COUNT', 'patient');
define('FIELD_PATTERN', 'pattern');
define('FIELD_PAYTIME', 'paytime');
define('FIELD_PR', 'pr');
define('FIELD_PRINTED', 'printed');
define('FIELD_PROD_BRAND', 'product_brand');
define('FIELD_PROD_ID', 'product_id');
define('FIELD_PROD_NAME', 'product_name');
define('FIELD_PROFILE', 'profile');
define('FIELD_POSITION', 'position');
define('FIELD_QTY', 'qty');
define('FIELD_RADIAL', 'radial');
define('FIELD_REF', 'ref');
define('FIELD_REFUND', 'refund');
define('FIELD_REFUNDTIME', 'refundtime');
define('FIELD_REPEAT', 'repeat');
define('FIELD_RIM', 'rim');
define('FIELD_RIM_WIDTH', 'rim_width');
define('FIELD_ROUND_OFF', 'round_off');
define('FIELD_ROUND_REFUND', 'round_refund');
define('FIELD_SALE_DATE', 'sale_date');
define('FIELD_SALE_ID', 'sale_id');
define('FIELD_SCHEDULE', 'schedule');
define('FIELD_SESSION_ID', 'session_id');
define('FIELD_SLMC', 'slmc');
define('FIELD_SPECIALITY', 'speciality');
define('FIELD_SPEED_RATING', 'speed_rating');
define('FIELD_START_DATE', 'start_date');
define('FIELD_STATUS', 'status');
define('FIELD_SUB_CATEGORY', 'sub_category');
define('FIELD_SUMMARY', 'summary');
define('FIELD_SW', 'sw');
define('FIELD_TEL', 'telephone');
define('FIELD_TITLE', 'title');
define('FIELD_TIME', 'time');
define('FIELD_TIMESTAMP', 'timestamp');
define('FIELD_TIMESTAMP_MAX', 'max_timestamp');
define('FIELD_TIMESTAMP_MIN', 'min_timestamp');
define('FIELD_TOP_CATEGORY', 'top_category');
define('FIELD_TUBELESS', 'tubeless');
define('FIELD_TYPE', 'type');
define('FIELD_UNIT_PRICE', 'unit_sale_price');
define('FIELD_USER', 'user');
define('FIELD_USER_ID', 'user_id');
define('FIELD_VAL', 'val');
define('FIELD_VAT', 'vat');
define('FIELD_VAT_REFUND', 'vat_refund');
define('FIELD_VEHI_NO', 'vehicle_no');
define('FIELD_WIDTH', 'width');
define('FIELD_YEAR', 'year');
define('FIELD_TYPE_INT', 1);
define('FIELD_TYPE_STR', 2);
define('FIELD_TYPE_OTHER', 3);

define('JSON_SUCCESS', "success");
define('JSON_STATUS', "status");
define('JSON_DETAILS', "details");
define('JSON_TOTAL', "total");

define('OK_DATA_DELETE', "Successfully removed the record");
define('OK_DATA_EMPTY_SET', "No data to display");
define('OK_DATA_INSERT', "Successfully added data");
define('OK_DATA_READ', "Successfully read data");
define('OK_DATA_UPDATE', "Successfully updated data");
define('OK_DB_BACKUP', "Successfully backed-up the database");
define('OK_DB_RESTORE', "Successfully restored database");
define('OK_LOGIN', "Successful user login");
define('OK_LOGOUT', "Successful user logout");
define('OK_USER', "User valid");

define('REPORT_DAILY', "read");
define('REPORT_DOC_PAY', "doc_pay");
define('REPORT_MONTHLY', "monthly");
define('REPORT_REVENUE', "revenue");
define('REPORT_SALES', "sales");
define('REPORT_SUMMARY', "summary");

define('SMS_DR', "dr");
define('SMS_DST', 'dst');
define('SMS_MSG', 'msg');
define('SMS_PASS', 'password');
define('SMS_RESP', 'response');
define('SMS_SRC', "src");
define('SMS_SOURCE', "source");
define('SMS_USER', 'username');

define('STATUS_APPOINTMENT_OK', 0);
define('STATUS_APPOINTMENT_RESERVE', 1);
define('STATUS_APPOINTMENT_REFUND', 2);

define('TBL_AGENT', 'tbl_agent');
define('TBL_APPOINTMENT', 'tbl_appointment');
define('TBL_CATEGORY', 'tbl_category');
define('TBL_CUSTOMER', 'tbl_customer');
define('TBL_DOCTOR', 'tbl_doctor');
define('TBL_FEEDBACK', 'tbl_feedback');
define('TBL_OFFICER', 'tbl_officer');
define('TBL_PRODUCT', 'tbl_product');
define('TBL_PRODUCT_CATEGORY', 'tbl_product_category');
define('TBL_PROMOTION', 'tbl_promotion');
define('TBL_SALE', 'tbl_sale');
define('TBL_SALE_FOLLOWUP', 'tbl_sale_followup');
define('TBL_SCHEDULE', 'tbl_schedule_summary');
define('TBL_SCHEDULE_INSTANCE', 'tbl_schedule_instance');
define('TBL_USER', 'tbl_user');
define('TBL_USER_PARA', 'tbl_user_para');

define('VIEW_CATEGORY', 'view_category');
define('VIEW_PRODUCT_CATEGORY', 'view_product_category');

define('USER_TYPE_ADMIN', 'admin');
define('USER_TYPE_FITTER', 'fitter');
define('USER_TYPE_USER', 'user');
define('USER_ID_MB', 'user_id_mb');
define('TEXT_MESSAGE', 'message');

// Order of the SMS parameters for sales record
$SMS_PARA_ORDER = array (
  FIELD_NAME, FIELD_TEL, FIELD_CATEGORY, FIELD_VEHI_NO, FIELD_ODOMETER_READING,
  FIELD_PROD_NAME, FIELD_POSITION, FIELD_QTY, FIELD_AGENT_ID, FIELD_PATTERN,
  FIELD_UNIT_PRICE, FIELD_COMMENT
);

// Valid SMS product categories
$SMS_PROD_CAT = array(
  "3" => '3-Wheeler', "A" => 'Agriculture', "B" => 'Bus', "C" => 'Car', 
  "I" => 'Industrial', "M" => 'Motorbike', "S" => 'SUV', "T" => 'Truck', 
  "TW" => '3-Wheeler', "V" => 'Van'
);

// Valid tyre possitions via SMS
$SMS_PROD_POS = array(
  "A" => "All", "F" => "FrontAll", "FA" => "FrontAll", "FL" => "FrontLeft", "FR" => "FrontRight", 
  "R" => "RearAll", "RA" => "RearAll", "RL" => "RearLeft", "RR" => "RearRight"
);
?>