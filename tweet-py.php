<?php

  /* --------------------------------------------- FOR USE WITH THE PYTHON LISTENER ------------------------------- */

  
  $type = 'twitter'; require('keys.php');
	require_once("functions.php");
	function processMessage($convo_id, $say, $user) {
	  // process incoming message
	  $bot_id = checkBot($say);
	  $sendbot = ($bot_id == 0) ? '' : "&bot_id=".$bot_id;
	  // Agrega el identificador de la interface
	  $convo_id = "twi-".$convo_id;
	  $userat = '@'.$user;
	  $say = str_ireplace($userat, '', $say);
	   if (isset($say)) {
			 $responseurl = PATH_TO_BOT.'?say='.urlencode($say).'&convo_id='.$convo_id.$sendbot.'&format=json';
		  	 $response = file_get_contents($responseurl);
			 if(!$response){
				 $badresponse = Array("convo_id" => $convo_id, "usersay" => $say, "botsay" => "Lo siento, no he podido leer bien el mensaje. Vuelve mas tarde por favor!");
			 	print json_encode($badresponse);
			 }else{
			 	print $response;
			 }
	   } 
	}
	
	$update = $_POST["update"];
	
	if (!$update) {
	  // receive wrong update, must not happen
	  exit;
	}

	if (isset($update)) {
		$user = $_POST["user"];
		$convo_id = $_POST["convo_id"];
		$say = $_POST["say"];
	  processMessage($convo_id, $say, $user);
	}

  ?>



