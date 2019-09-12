<?php
// Bypassing Access Control Allow Origin
// Ref: https://stackoverflow.com/questions/7564832/how-to-bypass-access-control-allow-origin
header("Access-Control-Allow-Origin: *");

require_once dirname(__FILE__).'/session.php';
function send_sms($msg, $dst) {
  //http://sms.textware.lk:5000/sms/send_sms.php?username=aasait1&password=aasait1&src=94115708132&dst=94773785550&msg=Test+SMS&dr=1

  $send_sms_api_call = TW_SMS_API_BASE . "?" . 
    SMS_SRC . "=" . TW_SMS_API_SRC . "&" .
    SMS_MSG . "=" . urlencode($msg) . "&" . 
    SMS_DST . "=" . $dst . "&" .
    SMS_PASS . "=" . TW_SMS_API_PASS . "&" .
    SMS_DR . "=" . TW_SMS_API_DR . "&" .
    SMS_USER . "=" . TW_SMS_API_USER;
  $resp = file_get_contents($send_sms_api_call);
  info(__FILE__, __FUNCTION__, __LINE__, $resp, $send_sms_api_call);
  return $resp;
}
?>
