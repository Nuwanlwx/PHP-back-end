<?php
require_once dirname(__FILE__).'/session.php';

$request[FIELD_ARCHIVE] = "true";

switch ($action) { //$action variable defined in session.php
  case REPORT_DAILY:
    daily_report($request);
    break;
  case REPORT_MONTHLY:
    monthly_report($request);
    break;
  case REPORT_SALES:
    sales_report($request);
    break;
  case REPORT_REVENUE:
    revenue_report($request);
    break;
  case REPORT_SUMMARY:
    summary_report($request);
    break;
  case REPORT_DOC_PAY:
    doc_pay_report($request);
    break;
  default:
    $error = ERR_UNKNOWN_ACTION;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $request);
    fail_return($error);
    break;
}

function daily_report() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;

  if(isset($args[FIELD_DOCTOR]) && !is_numeric($args[FIELD_DOCTOR])) {
    $args[TBL_DOCTOR.".".FIELD_NAME] = $args[FIELD_DOCTOR];
    unset($args[FIELD_DOCTOR]);
  }
  
  require_once 'db.php';
  return db_daily_report(TBL_APPOINTMENT, $args);
}

function monthly_report() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;  
  require_once 'db.php';
  return db_monthly_report(TBL_APPOINTMENT, $args);
}

function revenue_report() {
  $ret = array (
  'title' => 'Revenue report (2012/01/01-2012/01/31)',
  'head_row' => array('Doctor', 'Visits', 'Appointments', 'Consultancy Charges', 'Institute Charges'),
  'data_rows' => array(
  array('Dr. Anjana Karunanayake', 12000, 3000),
  array('Dr. Maneesh Weththasinghe', 18000, 4200),
  array('Dr. Gayan Kuruwita', 23400, 4650),
  array('Dr (Mrs). Anjalee Jayanthi', 18000, 3400)),
  'last_row' => array('Total', 71400, 18650));
  
  return succ_return($ret);
}

function summary_report() {
  $ret = array (
  'title' => 'Daily(/Weekly/Monthly) Summary from 2011/01/01 for 2 day(week/month/s)',
  'head_row' => array('Period', 'Doctors', 'Appointments', 'Doctor Charges', 'Institute Charges'),
  'data_rows' => array(
  array('2012/01/01', 5, 40, 45700, 12300),
  array('2012/01/02', 7, 52, 84200, 13700)),
  'last_row' => array('Total', 12, 92, 129900, 26000));
  return succ_return($ret);
}

function sales_report() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;

  if(isset($args[FIELD_DOCTOR]) && !is_numeric($args[FIELD_DOCTOR])) {
    $args[TBL_DOCTOR.".".FIELD_NAME] = $args[FIELD_DOCTOR];
    unset($args[FIELD_DOCTOR]);
  }
  
  $date = date("Y-m-d");
  $time = date("H:i:s");
  $from = isset($args["from_date"]) ? $args["from_date"] : $date;
  $to = isset($args["to_date"]) ? $args["to_date"] : $from;
  
  $report_file = "/report-$from-$to.htm";
  if($from == $to) {
    $report_file = "/report-$from.htm";
  }

  if (!file_exists(REPORT_DIR.$report_file) || $date <= $from || $date <= $to) {
    $args["from_date"] = $from;
    $args["to_date"] = $to;
    require_once 'db.php';
    $report = "<html><body><style type=\"text/css\">td {font-family:\"Verdana\";font-size:11px}</style><table>";
    $doc_fee = 0;
    $inst_fee = 0;
    $vat = 0;
    $round_off = 0;
    $user = $_SESSION[FIELD_USER];
    $info = INSTITUTE_NAME;
    //date_time, ref, patient, appointment_date, name as doctor, doc_fee, inst_fee
    $report .= "<tr><td colspan=6 align=right><h3 align=left>$info</h3>Reports generated from $from to $to by $user on $date @ $time</td></td></tr>";
    $data = db_collection_report(TBL_APPOINTMENT, $args);
    $report .= "<tr><td colspan=8 align=right><h4 align=left>SALES</h4></tr>";
    $report .= "<tr><td align=right><b>Invoice #</b></td><td><b>Patient</b></td><td><b>Appointment Date</b></td><td><b>Doctor</b></td><td align=right><b>Doctor Fee</b></td><td align=right><b>Institution Fee</b></td><td align=right><b>VAT</b></td><td align=right><b>Round-off</b></td></tr>";
    foreach ($data as $row) {
      $report .= "<tr>";
      foreach ($row as $key => $val) {
        switch ($key) {
          case "vat":
            $vat += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "round_off":
            $round_off += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "doc_fee":
            $doc_fee += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "inst_fee":
            $inst_fee += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "ref":
            $report .= "<td align=right>$val</td>";
            break;
          default:
            $report .= "<td>$val</td>";
        }
      }
      $report .= "</tr>";
    }
    $report .= "<tr><td colspan=4><b>Total</b></td><td align=right><b>".number_format($doc_fee, 2)."</b></td><td align=right><b>".number_format($inst_fee,2)."</b></td><td align=right><b>".number_format($vat,2)."</b></td><td align=right><b>".number_format($round_off,2)."</b></td></tr>";
    $report .= "<tr><td colspan=8></td></tr>";
    
    $doc_fee = 0;
    $inst_fee = 0;
    $vat = 0;
    $round_off = 0;
    $data = db_refund_report(TBL_APPOINTMENT, $args);
    $report .= "<tr><td colspan=8></td></tr>";
    $report .= "<tr><td colspan=8 align=right><h4 align=left>REFUNDS</h4></td></tr>";
    $report .= "<tr><td align=right><b>Invoice #</b></td><td><b>Patient</b></td><td><b>Appointment Date</b></td><td><b>Doctor</b></td><td align=right><b>Doctor Fee</b></td><td align=right><b>Institution Fee</b></td><td align=right><b>VAT</b></td><td align=right><b>Round-off</b></td></tr>";
    foreach ($data as $row) {
      $report .= "<tr>";
      foreach ($row as $key => $val) {
        switch ($key) {
          case "vat":
            $vat += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "round_off":
            $round_off += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "doc_fee":
            $doc_fee += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "inst_fee":
            $inst_fee += $val;
            $report .= "<td align=right>$val</td>";
            break;
          case "ref":
            $report .= "<td align=right>$val</td>";
            break;
          default:
            $report .= "<td>$val</td>";
        }
      }
      $report .= "</tr>";
    }
    $report .= "<tr><td colspan=4><b>Total</b></td><td align=right><b>".number_format($doc_fee,2)."</b></td><td align=right><b>".number_format($inst_fee,2)."</b></td><td align=right><b>".number_format($vat,2)."</b></td><td align=right><b>".number_format($round_off,2)."</b></td></tr>";
    $report .= "</table></p></body></html>";

    $fh = fopen(REPORT_DIR.$report_file, 'w') or die("can't open file");
    fwrite($fh, $report);
    fclose($fh);
    info(__FILE__, __FUNCTION__, __LINE__, "report_generated_successfully");
  } else {
    info(__FILE__, __FUNCTION__, __LINE__, "report_already_exists");
  }
  return succ_return("../report".$report_file);
}
?>