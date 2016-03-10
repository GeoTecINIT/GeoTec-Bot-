<?php
/*********************************** 
	Load required dependencies
************************************/

require('functions.php');
require_once ("programo/config/global_config.php");


$time_start = microtime(true);
$script_start = $time_start;
$last_timestamp = $time_start;
$thisFile = __FILE__;

//load shared files
require_once(_LIB_PATH_ . 'PDO_functions.php');
include_once (_LIB_PATH_ . "error_functions.php");
include_once(_LIB_PATH_ . 'misc_functions.php');
ini_set('error_log', _LOG_PATH_ . 'getbots.error.log');

$dbConn = db_open();
$sql = "select `bot_id`, `bot_name`, `bot_active` from `$dbn`.`bots`;";
$result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);




$status = Array();
$status['level'] = 0; /*--- 0 = OK ; 1 = Atention ; 2 = Down ------ */

if(ESTADO_MANTENIMIENTO == 1){  /// Escape in case of maintennace
	$status['level'] = 2;
	$status['maintenance'] = Array('status'=>1, 'message'=>'En Mantenimiento!');
}else{ // Continue with the script
	$status['level'] = 0;
	$status['maintenance'] = Array('status'=>0, 'message'=>'Ok');
}
$bots = array();
foreach ($result as $row)
{
  $bot_id = $row['bot_id'];
  $bot_name = $row['bot_name'];
  $bot_active = $row['bot_active'];
  $botup = Array('id'=>$bot_id,'name'=>$bot_name, 'status'=>$bot_active);
  array_push($bots, $botup);
}
$status['bots']=$bots;

header('Content-type: application/json');	
$out = json_encode($status);
exit($out);
?>