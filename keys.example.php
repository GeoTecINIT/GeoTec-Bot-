<?php
	define('PATH_HOME', 'http://YOUR-PATH.com');
	$path_to_bot = PATH_HOME."/programo/chatbot/conversation_start.php";
	define('PATH_TO_BOT', $path_to_bot);
	switch ($type) {
		case 'sms':
			$sid = "TWILIO-ID"; // Your Account SID from www.twilio.com/user/account
			$token = "TWILIO-TOKEN"; // Your Auth Token from www.twilio.com/user/account
			break;
		case 'telegram':
			define('BOT_TOKEN', '11111111:YOUR-OWN-TOKEN');
			define('WEBHOOK_URL', PATH_HOME.'/PATH-TO-YOUR-PHP-TOKEN');
			break;
		case 'twitter':
	    $consumerkey="YOUR-KEY";
	    $consumersecret="-CONSUMER-SECRET";
	    $accesstoken="ACCESS-TOKEN";
	    $accesstokensecret="ACCESS-TOKEN-SECRET";
			break;
	}
?>