<?php
require_once dirname(__FILE__).'/session.php';

switch ($action) {
  case ACTION_BACKUP:
    backup($_REQUEST);
    break;
  case ACTION_CLEAN:
    clean($_REQUEST);
    break;
  case ACTION_RESTORE:
    restore($_REQUEST, $_FILES);
    break;
  default:
    warn(__FILE__, __FUNCTION__, __LINE__, ERR_UNKNOWN_ACTION, $_SESSION);
}

function backup() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!isset($args[FIELD_FILE])) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error);
    exit(fail_return($error, false));
  }
  $file = $args[FIELD_FILE];
  if(basename($file) == $file) {
    $file = BACKUP_DIR."/$file";
  }
  $dump_cmd = MYSQLDUMP." -u ".DB_USER." -p".DB_PASS." ".DB_NAME;
  $output = `$dump_cmd > $file`;
  $echo = (isset($args["echo"])) ? $args["echo"] : true;
  if ($output) {
    warn(__FILE__, __FUNCTION__, __LINE__, $dump_cmd, $file, $output);
    return fail_return(ERR_DB_BACKUP, $echo);
  } else {
    info(__FILE__, __FUNCTION__, __LINE__, OK_DB_BACKUP, $file);
    return succ_return(OK_DB_BACKUP, $echo);
  }
}

function restore() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  if (!isset($args[1][FIELD_FILE]['tmp_name'])) {
    $error = ERR_PARA_NOT_DEFINED;
    warn(__FILE__, __FUNCTION__, __LINE__, $error);
    exit(fail_return($error, false));
  }
  $file = $args[1][FIELD_FILE]['tmp_name'];
  if (!file_exists($file)) {
    $error = ERR_FILE_NOT_EXISTS;
    warn(__FILE__, __FUNCTION__, __LINE__, $error, $file);
    exit(fail_return($error, false));
  }
  require_once 'db.php';
  return db_restore($file);
}

function clean() {
  $args = func_get_args();
  $args = (sizeof($args) == 1) ? $args[0] : $args;
  require_once 'db.php';
  backup(array(FIELD_FILE => date('Ymd')."-".date('His')."-auto-backup.sql", "echo" => false));
  return db_clean($args[FIELD_DATE]);
}
?>