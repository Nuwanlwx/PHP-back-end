<?php
define('TIME_ZONE', 'Asia/Colombo');
define('DB_NAME', '');
define('DB_SERVER', 'localhost');
define('DB_USER', 'ceat');
define('DB_PASS', '');
// define('DB_SERVER', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');



define('DOC_ROOT', dirname(__FILE__).'/../..');
define('BACKUP_DIR', DOC_ROOT.'/backup');
define('REPORT_DIR', DOC_ROOT.'/report');
define('AUDIT_LOG_DIR', DOC_ROOT.'/audit');
define('MYSQLDUMP', DOC_ROOT.'/../mysql/bin/mysqldump.exe');

define('MAX_SCHEDULE_COUNT', 1000);

// SMS API specific configurations
define('SMS_API_MODULE', "sms_ds.php");

// Dialog Digital Service SMS API configurations
define('DS_SMS_API_SEND', 'https://req.php');
define('DS_SMS_API_TOKEN', 'https://g.lk/refresh_token.php');
define('DS_SMS_API_METHOD', "POST");
define("DS_SMS_API_CALLBACK", "http://dev.asms_dr.php");
define("DS_SMS_API_FROM", "Demo");
define("DS_SMS_API_CHANNEL", "9");
define('DS_SMS_API_USER', "");
define('DS_SMS_API_PASS', "");
define('DS_SMS_API_IPS', ",");

// Textware SMS API configurations
define('TW_SMS_API_BASE', "http://sms.tsend_sms.php");
define('TW_SMS_API_USER', "aa");
define('TW_SMS_API_PASS', "aa");
define('TW_SMS_API_SRC', "9401458755");
define('TW_SMS_API_DR', "1");
define('SMS_PERMITTED_IPS', "::1,,,"); //Comma seperated IPs; NO SPACES  
?>
