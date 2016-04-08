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
function randomErrorPhrases(){
	
	$phrases = array(
	    'Ups! Parece que hubo un cortocircuito en mi sistema... ¿qué decías?',
	    'Vale, muy bien. No se que decirte...',
	    'A que te refieres?',
		'Ups, me han llamado de otro lado. Me lo repites?',
		'si, si, si',
		'¯\_(ツ)_/¯',
		'Dime algo que no sepa',	
		'Bueno, si te parece hablamos en otro momento',
		'AAAahhhhhh, estoy tan agotado... ',
		'Muy bien, qué te parece si seguimos la conversación más tarde?',
		'¿Sabes que es mi hora de descanso justo ahora? Hablemos mas tarde por favor.',
		'¿Te gustan los helados? ¿Podrías ir a comprar uno para regalarselo a alguien?',
		'¿Por qué no aprovechas a suscribirte a mi lista de correos para ayudarnos a mejorar mi plataforma? http://holabot.es/suscribirse.php'
	  );

	  return $phrases[array_rand($phrases)];
  }
  
function processResponse($responseurl){
 	 if($responseurl != ''){
 	 	$response = file_get_contents($responseurl);
 	 	if(!$response){
			$response['botsay'] = randomErrorPhrases();
 	 	}else{
 	 		$response = json_decode($response, true);
		}
 	 }else{
 	 	$response['botsay'] = "No has enviado ningún mensaje, lo siento pero no puedo responderte.";	
 	 } 
	 return $response;
}
?>
