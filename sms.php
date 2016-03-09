<?php
/*******************************************************************************
An example HTML interface to the JxBot programmers API
Customise this webpage or make your own.
*******************************************************************************/

/* include the JxBot client API */

// Install the library via PEAR or download the .zip file to your project folder.
// This line loads the library
require('twilio-php/Services/Twilio.php');
require('functions.php');
$type = 'sms'; require('keys.php');
$client = new Services_Twilio($sid, $token);
$msg =  (!empty($_REQUEST['Body'])) ? $_REQUEST['Body'] : '';
/* ------- reemplaz message con un mensaje local para probar! */ if (isset($_GET['body'])) $msg = $_GET['body'];

// Define el bot y lo guarda en una cookie. Si le digo "hola ..." reinicia el robot dependiendo el que sea. Si es el mismo lo busca en la cookie.
$botId = null; 
$getBot = checkBot($msg);
$bot_id = ($getBot > 1) ? $bot_id = $getBot : (isset($_COOKIE['bot_id'])) ? $_COOKIE['bot_id'] :($bot_id !== null) ? checkBot($msg) : 1;
setcookie('bot_id', $bot_id);
$sendbot = ($bot_id == 0) ? '' : "&bot_id=".$bot_id;
if (isset($_REQUEST['From'])) { $tel = ";/".$_REQUEST['From'];} else { $tel = "";}  //verifica que sea desde un sms y copia el telefono en la variable $tel y le agrega ;/ adelante para diferenciar de la sesión

// Define la cookie y le asigna una sesión y numero de teléfono
$cookie_name = 'Program_O_JSON_GUI';

$convo_id = (isset($_COOKIE[$cookie_name])) ? $_COOKIE[$cookie_name] : jq_get_convo_id($tel);

// Agrega el identificador de la interface
$convo_id = "sms-".$convo_id;

/* ------- Enviar mensaje -------- */

$responseurl = $path_to_bot.'?say='.rawurlencode($msg).$sendbot.'&convo_id='.$convo_id.'&format=json';

if($responseurl != ''){
	$response = file_get_contents($responseurl);
	$response = json_decode($response, true);
}

	/* Imprime los cabezales XML */
	header("content-type: text/xml");
	print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

if (isset($response)) {  // verifica que haya una respuesta
		print '<Response> <Message>'; 
		print $response['botsay'];
		print '</Message> </Response>';
	}else{
		print '<Response> <Message>';
		print "Error:". $response['botsay'];
		print '</Message> </Response>';
	}

?>