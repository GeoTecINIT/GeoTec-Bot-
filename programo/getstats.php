<?php
  /***************************************
  * http://www.program-o.com
  * PROGRAM O
  * Version: 2.4.8
  * FILE: getbots.php
  * AUTHOR: Elizabeth Perreau and Dave Morton
  * DATE: MAY 17TH 2014
  * DETAILS: Searches the database for all active chatbots, returning a JSON encoded array of ID/name pairs
  ***************************************/

  $time_start = microtime(true);
  $script_start = $time_start;
  $last_timestamp = $time_start;
  $thisFile = __FILE__;
  require_once ("config/global_config.php");
  //load shared files
  require_once(_LIB_PATH_ . 'PDO_functions.php');
  include_once (_LIB_PATH_ . "error_functions.php");
  include_once(_LIB_PATH_ . 'misc_functions.php');
  ini_set('error_log', _LOG_PATH_ . 'getbots.error.log');

  if(isset($_GET['user'])){ $user = $_GET['user'];} else{$user="";}
  if(isset($_GET['bot'])){ $bot = $_GET['bot'];} else{$bot="";}
   if(isset($_GET['day'])){ $day = $_GET['day'];} else{$day="";}
   if(isset($_GET['s'])){ $s = $_GET['s'];} else{$s="";}
   
  $dbConn = db_open();
  if($bot != ""){
	  $sql1 = "SELECT COUNT(*) as total from `conversation_log` WHERE `bot_id` = '$bot';";
	  $sql = "SELECT user_id as bot, DATE(timestamp) as day, id as total from `conversation_log` WHERE bot_id = '$bot' GROUP BY day, user_id ORDER BY day ASC;";
  }else if($user != ""){
	  $sql1 = "SELECT COUNT(*) as total from `conversation_log` WHERE `user_id` = '$user';";
	  $sql = "SELECT bots.bot_name as bot, DATE(conversation_log.timestamp) as day, conversation_log.id as total from `conversation_log` LEFT JOIN bots ON conversation_log.bot_id=bots.bot_id WHERE conversation_log.user_id = '$user' GROUP BY day, conversation_log.bot_id ORDER BY day ASC;";
  }else if($day != ""){
	  $sql1 = "SELECT COUNT(*) as total from `conversation_log` WHERE DATE(`timestamp`) = '$day';";
	  $sql = "SELECT `timestamp`, `user_id`, `bot_id`, `org_input`, `response` from `conversation_log` WHERE DATE(`timestamp`) = '$day' ORDER BY `timestamp` ASC;";
  }else if($s != ""){
	  $sql1 = "SELECT COUNT(*) as total from `conversation_log`";
	  $sql = "SELECT bots.bot_name as bot, DATE(conversation_log.timestamp) as day, conversation_log.id as total  FROM `conversation_log` LEFT JOIN bots ON conversation_log.bot_id=bots.bot_id GROUP BY conversation_log.bot_id, day;";
  }else{
	  $sql1 = "SELECT COUNT(*) as total from `conversation_log`";
	  $sql = "SELECT `id`, `bot_id`, COUNT(*) as total from `conversation_log` GROUP BY `bot_id`;";
  }
	  $result1 = db_fetchAll($sql1, null, __FILE__, __FUNCTION__, __LINE__);
	  $result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);
	  $bots = array('conversation_log'=>$result, 'total_logs'=>$result1);

  
 /* foreach ($result as $row)
  {
    $bot_id = $row['bot_id'];
  //  $bot_total = $row['COUNT(*)'];
   // $bots['conversation_log']['log'][$log_id] = $bot_total;
  }*/
 header("Access-Control-Allow-Origin: *");   
 header('Content-type: application/json');
  $out = json_encode($bots);
  exit($out);
