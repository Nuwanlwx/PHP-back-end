<?php
// Bypassing Access Control Allow Origin
// Ref: https://stackoverflow.com/questions/7564832/how-to-bypass-access-control-allow-origin
header("Access-Control-Allow-Origin: *");

require_once dirname(__FILE__).'/session.php';
function send_sms($msg, $dst) {
  // Dialog Digital Reach API implementation

  // Check whether we already have DS Access Tocken
  $ds_token = isset($GLOBALS[DS_SMS_API_TOKEN]) ? $GLOBALS[DS_SMS_API_TOKEN] : false;
  if (!$ds_token) {
    $ds_token = ds_get_token();
    if(!$ds_token) {
      warn(__FILE__, __FUNCTION__, __LINE__, ERR_API, $msg, $dst, $ds_token);
      return $ds_token;
    }
  }

  $resp = ds_send_sms($msg, $dst, $ds_token);

  if (!$resp) {
    // Refresh Token
    $ds_token = ds_refresh_token();
    if ($ds_token) {
      $resp = send_ds_sms($msg, $dst, $ds_token);
    } else {
      warn(__FILE__, __FUNCTION__, __LINE__, ERR_API, $msg, $dst, $ds_token);
      return $ds_token;
    }
  }
  info(__FILE__, __FUNCTION__, __LINE__, $resp, $msg, $dst);
  return $resp;
}

function ds_get_token() {
  // $url = 'https://digitalreachapi.dialog.lk/refresh_token.php'; 
  $token_data = array(
    "u_name" => DS_SMS_API_USER, 
    "passwd" => DS_SMS_API_PASS
  ); 
  
  $headers = array(
    'Content-Type: application/json'
  );

  $resp = ds_api_call(DS_SMS_API_METHOD, DS_SMS_API_TOKEN, $token_data, $headers);
  if ($resp) {
    $resp = json_decode($resp);
    $resp = $resp->{"access_token"};
  }
  return $resp;
}

function ds_send_sms($msg, $dst, $ds_token) {
  $sms_data = array(
    "msisdn" => $dst,
    "channel" => DS_SMS_API_CHANNEL, 
    "mt_port" => DS_SMS_API_FROM, 
    "s_time" => date("Y-m-d G:i:s"), 
    "e_time" => date("Y-m-d G:i:s", strtotime("+7 day")), 
    "msg" => $msg, 
    "callback_url" => DS_SMS_API_CALLBACK
  );

  $headers = array(
    'Content-Type: application/json',
    'Authorization: '.$ds_token
  );

  return ds_api_call(DS_SMS_API_METHOD, DS_SMS_API_SEND, $sms_data, $headers);
}

function ds_api_call($method, $url, $data = false, $headers = array()) { 
  $curl = curl_init();
  switch ($method) {
    case "POST": 
      curl_setopt($curl, CURLOPT_POST, 1);
      if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      break;
    case "PUT": 
      curl_setopt($curl, CURLOPT_PUT, 1); 
      break;
    default:
      if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
  }

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_POST, 1); 
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  
  $resp = curl_exec($curl);
  
  if (!$resp) {
    $err = stripslashes(curl_error($curl)); 
    warn(__FILE__, __FUNCTION__, __LINE__, $err, $resp, $method, $url, $data, $headers);
  }

  curl_close($curl);

  return $resp;
}
?>
