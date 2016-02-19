<?php
require_once('functions.php');
$msg = $_GET['msg'];
$bot = checkBot($msg);
print_r("mensaje: ".$msg." - Bot: ".$bot);
?>