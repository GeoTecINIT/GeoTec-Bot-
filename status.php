<?php
/*********************************** 
	Load required dependencies
************************************/

require('functions.php');

if(ESTADO_MANTENIMIENTO == 1){  /// Escape in case of maintennace
exit("Mantenimiento!");	
}else{ // Continue with the script
exit("OK");	
}?>