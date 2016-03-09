<?php

 /* -------- MAINTENANCE MODE SETTINGS ---- 0 = No - 1 = Yes ------- */
define('ESTADO_MANTENIMIENTO', 0);
define('ERROR_MANTENIMIENTO', '[EN MANTENIMIENTO] Lo siento, pero estoy en el servicio mecánico, por favor intenta luego.');

function getBots(){
	$responseurl = PATH_HOME.'/programo/getbots.php';
	$response = array();
	if($responseurl != ''){
		$response = file_get_contents($responseurl);
		$response = json_decode($response, true);
	}
	$array = $response['bots'];
	$bots = array();
	foreach($array as $bot){
		$bots[] = $bot; 
	}
	return $bots;
}
function checkBot($msg){
	$result = 0;
	$msgspaced = normalacentos($msg);
	$msgspaced = str_replace('%20', ' ', $msgspaced);
	$bots = getBots();
	if (stripos($msgspaced, 'hola') !== false){
			$result = 1;
			foreach($bots as $key => $item){
				$string = normalacentos($item);
				if (stripos($msgspaced, $string) !== false){
					$temp_result = $key+1;
				} 
			}
			if($temp_result > 1) { $result = $temp_result; }
	}
	return $result;
}
function normalacentos($String){
	$String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
	$String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
	$String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
	$String = str_replace(array('í','ì','î','ï'),"i",$String);
	$String = str_replace(array('é','è','ê','ë'),"e",$String);
	$String = str_replace(array('É','È','Ê','Ë'),"E",$String);
	$String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
	$String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
	$String = str_replace(array('ú','ù','û','ü'),"u",$String);
	$String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
	$String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
	$String = str_replace("ç","c",$String);
	$String = str_replace("Ç","C",$String);
	$String = str_replace("ñ","n",$String);
	$String = str_replace("Ñ","N",$String);
	$String = str_replace("Ý","Y",$String);
	$String = str_replace("ý","y",$String);
	return $String;
}
function jq_get_convo_id($tel)
{
  global $cookie_name;
  session_name($cookie_name);
  session_start();
  $convo_id = session_id(). $tel;
  session_destroy();
  setcookie($cookie_name, $convo_id);
  return $convo_id;
}
function processResponse($responseurl){
 	 if($responseurl != ''){
 	 	$response = file_get_contents($responseurl);
 	 	if(!$response){
			$response = "Lo siento, no he podido leer bien el mensaje. Vuelve mas tarde por favor!";
 	 	}else{
 	 		$response = json_decode($response, true);
		}
 	 }else{
 	 	$response = "No has enviado ningún mensaje, lo siento pero no puedo responderte.";	
 	 } 
	 return $response;
}
?>
